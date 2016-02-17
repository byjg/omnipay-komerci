<?php

use Omnipay\Common\CreditCard;
use Omnipay\Komerci\Gateway;
use Omnipay\Tests\GatewayTestCase;

class KomerciWSGatewayTest extends GatewayTestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $card = new CreditCard($this->getValidCard());

        $this->purchaseOptions = array(
            'amount' => 95.63,
            'card' => $card,
            'apikey' => '1234567890',
            'transactionId' => '9966441'
        );

        $this->captureOptions = array(
            'amount' => 95.63,
            'apikey' => '1234567890',
            'transactionReference' => '0123456',
            'numautor' => '7890123',
            'username' => 'user',
            'password' => 'pass'
        );

        $this->voidOptions = array(
            'accountNumber' => '12345678',
            'storeId' => 'test',
            'storePassword' => 'test',
            'transactionReference' => '115147689',
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        $request = $this->gateway->authorize($this->purchaseOptions);
        $requestData = $request->getData();
        /** @var $card CreditCard */
        $card = $request->getCard();

        $response = $request->send();

        // Validate Request
        $this->assertSame('95.63', $requestData['Total']);
        $this->assertSame('73', $requestData['Transacao']);
        $this->assertEmpty($requestData['Parcelas']);
        $this->assertSame('1234567890', $requestData['Filiacao']);
        $this->assertSame('9966441', $requestData['NumPedido']);
        $this->assertSame($this->purchaseOptions['card']->getNumber(), $requestData['Nrcartao']);
        $this->assertSame($this->purchaseOptions['card']->getCvv(), $requestData['CVC2']);
        $this->assertSame($this->purchaseOptions['card']->getExpiryMonth(), $requestData['Mes']);
        $this->assertSame($this->purchaseOptions['card']->getExpiryYear(), $requestData['Ano']);
        $this->assertSame($this->purchaseOptions['card']->getName(), $requestData['Portador']);
        $this->assertEmpty($requestData['IATA']);
        $this->assertEmpty($requestData['Distribuidor']);
        $this->assertEmpty($requestData['Concentrador']);
        $this->assertEmpty($requestData['TaxaEmbarque']);
        $this->assertEmpty($requestData['Entrada']);
        $this->assertEmpty($requestData['Numdoc1']);
        $this->assertEmpty($requestData['Numdoc2']);
        $this->assertEmpty($requestData['Numdoc3']);
        $this->assertEmpty($requestData['Numdoc4']);
        $this->assertEmpty($requestData['Pax1']);
        $this->assertEmpty($requestData['Pax2']);
        $this->assertEmpty($requestData['Pax3']);
        $this->assertEmpty($requestData['Pax4']);
        $this->assertSame('S', $requestData['ConfTxn']);
        $this->assertEmpty($requestData['AddData']);

        // Validate Response
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('0', $response->getCode());
        $this->assertSame('123409876', $response->getTransactionReference());
        $this->assertSame('Autorizado com sucesso', $response->getMessage());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockHttpResponse('AuthorizeFailure.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEmpty($response->getTransactionReference());
        $this->assertSame('TRANSAÇÃO NEGADA', $response->getMessage());
    }

    public function testAuthorizeFailure_2ndFormat()
    {
        $this->setMockHttpResponse('AuthorizeFailure2.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('TRANSACAO JA CONFIRMADA', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        // Confirm Pre-Auth
        $this->setMockHttpResponse('ConfPreAuthSuccess.txt');

        $request = $this->gateway->capture($this->captureOptions);
        $requestData = $request->getData();
        /** @var $card CreditCard */
        $card = $request->getCard();

        $response = $request->send();

        // Validate Request
        $this->assertSame('1234567890', $requestData['Filiacao']);
        $this->assertEmpty($requestData['Distribuidor']);
        $this->assertSame('95.63', $requestData['Total']);
        $this->assertSame('00', $requestData['Parcelas']);
        $this->assertSame('0123456', $requestData['NumCv']);
        $this->assertSame('7890123', $requestData['NumAutor']);
        $this->assertEmpty($requestData['Concentrador']);
        $this->assertSame('user', $requestData['Usr']);
        $this->assertSame('pass', $requestData['Pwd']);

        // Validate Response
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('0', $response->getCode());
        $this->assertEmpty($response->getTransactionReference());
        $this->assertSame('Sucesso', $response->getMessage());
    }

    public function testCaptureSuccess_Installments()
    {
        // Confirm Pre-Auth
        $this->setMockHttpResponse('ConfPreAuthSuccess.txt');

        $this->captureOptions['installments'] = 2;
        
        $request = $this->gateway->capture($this->captureOptions);
        $requestData = $request->getData();
        /** @var $card CreditCard */
        $card = $request->getCard();

        $response = $request->send();

        // Validate Request
        $this->assertSame('1234567890', $requestData['Filiacao']);
        $this->assertEmpty($requestData['Distribuidor']);
        $this->assertSame('95.63', $requestData['Total']);
        $this->assertSame('02', $requestData['Parcelas']);
        $this->assertSame('0123456', $requestData['NumCv']);
        $this->assertSame('7890123', $requestData['NumAutor']);
        $this->assertEmpty($requestData['Concentrador']);
        $this->assertSame('user', $requestData['Usr']);
        $this->assertSame('pass', $requestData['Pwd']);

        // Validate Response
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('0', $response->getCode());
        $this->assertEmpty($response->getTransactionReference());
        $this->assertSame('Sucesso', $response->getMessage());
    }

    public function testCaptureFailure()
    {
        $this->setMockHttpResponse('ConfPreAuthFailure.txt');

        $response = $this->gateway->capture($this->captureOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEmpty($response->getTransactionReference());
        $this->assertSame('Dados+Inv%E1lidos.', $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        $request = $this->gateway->purchase($this->purchaseOptions);
        $requestData = $request->getData();
        /** @var $card CreditCard */
        $card = $request->getCard();

        $response = $request->send();

        // Validate Request
        $this->assertSame('95.63', $requestData['Total']);
        $this->assertSame('04', $requestData['Transacao']);
        $this->assertSame('00', $requestData['Parcelas']);
        $this->assertSame('1234567890', $requestData['Filiacao']);
        $this->assertSame('9966441', $requestData['NumPedido']);
        $this->assertSame($this->purchaseOptions['card']->getNumber(), $requestData['Nrcartao']);
        $this->assertSame($this->purchaseOptions['card']->getCvv(), $requestData['CVC2']);
        $this->assertSame($this->purchaseOptions['card']->getExpiryMonth(), $requestData['Mes']);
        $this->assertSame($this->purchaseOptions['card']->getExpiryYear(), $requestData['Ano']);
        $this->assertSame($this->purchaseOptions['card']->getName(), $requestData['Portador']);
        $this->assertEmpty($requestData['IATA']);
        $this->assertEmpty($requestData['Distribuidor']);
        $this->assertEmpty($requestData['Concentrador']);
        $this->assertEmpty($requestData['TaxaEmbarque']);
        $this->assertEmpty($requestData['Entrada']);
        $this->assertEmpty($requestData['Numdoc1']);
        $this->assertEmpty($requestData['Numdoc2']);
        $this->assertEmpty($requestData['Numdoc3']);
        $this->assertEmpty($requestData['Numdoc4']);
        $this->assertEmpty($requestData['Pax1']);
        $this->assertEmpty($requestData['Pax2']);
        $this->assertEmpty($requestData['Pax3']);
        $this->assertEmpty($requestData['Pax4']);
        $this->assertSame('S', $requestData['ConfTxn']);
        $this->assertEmpty($requestData['AddData']);

        // Validate Response
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('0', $response->getCode());
        $this->assertSame('123409876', $response->getTransactionReference());
        $this->assertSame('Autorizado com sucesso', $response->getMessage());
    }

    public function testPurchaseSuccess_Installments()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        $this->purchaseOptions['installments'] = 2;

        $request = $this->gateway->purchase($this->purchaseOptions);
        $requestData = $request->getData();
        /** @var $card CreditCard */
        $card = $request->getCard();

        $response = $request->send();

        // Validate Request
        $this->assertSame('95.63', $requestData['Total']);
        $this->assertSame('08', $requestData['Transacao']);
        $this->assertSame('02', $requestData['Parcelas']);
        $this->assertSame('1234567890', $requestData['Filiacao']);
        $this->assertSame('9966441', $requestData['NumPedido']);
        $this->assertSame($this->purchaseOptions['card']->getNumber(), $requestData['Nrcartao']);
        $this->assertSame($this->purchaseOptions['card']->getCvv(), $requestData['CVC2']);
        $this->assertSame($this->purchaseOptions['card']->getExpiryMonth(), $requestData['Mes']);
        $this->assertSame($this->purchaseOptions['card']->getExpiryYear(), $requestData['Ano']);
        $this->assertSame($this->purchaseOptions['card']->getName(), $requestData['Portador']);
        $this->assertEmpty($requestData['IATA']);
        $this->assertEmpty($requestData['Distribuidor']);
        $this->assertEmpty($requestData['Concentrador']);
        $this->assertEmpty($requestData['TaxaEmbarque']);
        $this->assertEmpty($requestData['Entrada']);
        $this->assertEmpty($requestData['Numdoc1']);
        $this->assertEmpty($requestData['Numdoc2']);
        $this->assertEmpty($requestData['Numdoc3']);
        $this->assertEmpty($requestData['Numdoc4']);
        $this->assertEmpty($requestData['Pax1']);
        $this->assertEmpty($requestData['Pax2']);
        $this->assertEmpty($requestData['Pax3']);
        $this->assertEmpty($requestData['Pax4']);
        $this->assertSame('S', $requestData['ConfTxn']);
        $this->assertEmpty($requestData['AddData']);

        // Validate Response
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('0', $response->getCode());
        $this->assertSame('123409876', $response->getTransactionReference());
        $this->assertSame('Autorizado com sucesso', $response->getMessage());
    }

}

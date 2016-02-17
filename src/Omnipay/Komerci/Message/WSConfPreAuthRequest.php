<?php

namespace Omnipay\Komerci\Message;

/**
 * Komerci Authorize Request
 */
class WSConfPreAuthRequest extends WSAbstractRequest
{

    public function getData()
    {
        $this->validate('apikey', 'amount', 'transactionReference', 'numautor', 'username', 'password');

        /*
          card
          token
          amount
          currency
          description
          transactionId
          clientIp
          returnUrl
          cancelUrl

          'firstName',
          'lastName',
          'number',
          'expiryMonth',
          'expiryYear',
          'startMonth',
          'startYear',
          'cvv',
          'issueNumber',
          'type',
          'billingAddress1',
          'billingAddress2',
          'billingCity',
          'billingPostcode',
          'billingState',
          'billingCountry',
          'billingPhone',
          'shippingAddress1',
          'shippingAddress2',
          'shippingCity',
          'shippingPostcode',
          'shippingState',
          'shippingCountry',
          'shippingPhone',
          'company',
          'email'
         */

        $data = array(
            'Filiacao' => $this->getApiKey(),
            'Distribuidor' => '',
            'Total' => sprintf("%.2F", round($this->getAmount() * 100) / 100),
            'Parcelas' => $this->getFormattedInstallments(),
            'Data' => date('%Y%m%d'),
            'NumAutor' => $this->getNumAutor(),
            'NumCv' => $this->getTransactionReference(),
            'Concentrador' => '',
            'Usr' => $this->getUsername(),
            'Pwd' => $this->getPassword()
        );

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->prepareSendData($data, 'ConfPreAuthorization');
        return $this->response = new WSConfPreAuthResponse($this, $httpResponse->xml());
    }
}

<?php

namespace Omnipay\Komerci\Message;

/**
 * Komerci Authorize Request
 */
class WSConfPreAuthRequest extends WSAbstractRequest
{
    protected $method = 'ConfPreAuthorization';

    public function getData()
    {
        $this->validate('amount', 'filiacao', 'numcv', 'numautor', 'parcelas');

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
        $data = array (
			'Filiacao'            => $this->getFiliacao(),
			'Distribuidor'        => '',
			'Total'               => sprintf("%.2F", round($this->getAmount() * 100)/100),
			'Parcelas'            => $this->getParcelas(),
			'Data'                => date('%Y%m%d'),
			'NumAutor'            => $this->getNumAutor(),
			'NumCv'               => $this->getNumCv(),
			'Concentrador'        => '',
			'Usr'                 => $this->getUsername(),
			'Pwd'                 => $this->getPassword()
		);

        return $data;
    }

    public function sendData($data)
    {
		$httpResponse = $this->prepareSendData($data);
        return $this->response = new WSConfPreAuthResponse($this, $httpResponse->xml());
    }

}
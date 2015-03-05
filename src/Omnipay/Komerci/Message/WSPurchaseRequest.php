<?php

namespace Omnipay\Komerci\Message;

/**
 * Komerci Authorize Request
 */
class WSPurchaseRequest extends WSAuthorizeRequest
{
    public function getData()
    {
		$data = parent::getData();
		$data['Transacao'] = $this->getTransacao();
		$data['Parcelas'] = $this->getParcelas();

        return $data;
    }

    public function sendData($data)
    {
		$httpResponse = $this->prepareSendData($data);
        return $this->response = new WSAuthorizeResponse($this, $httpResponse->xml());
    }

}
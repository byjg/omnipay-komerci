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
        $data['Transacao'] = $this->getInstallments() > 1 ? '08' : '04';  // Note: 04 - à vista; 08 - parcelado estabelecimento; 06 - Parcelado emissor
        $data['Parcelas'] = $this->getFormattedInstallments();

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->prepareSendData($data);
        return $this->response = new WSAuthorizeResponse($this, $httpResponse->xml());
    }
}

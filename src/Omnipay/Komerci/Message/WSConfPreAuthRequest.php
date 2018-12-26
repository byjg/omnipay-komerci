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

        $data = array(
            'Filiacao' => $this->getApiKey(),
            'Distribuidor' => '',
            'Total' => sprintf("%.2F", round($this->getAmount() * 100) / 100),
            'Parcelas' => $this->getFormattedInstallments(),
            'Data' => $this->getFormattedDate(),
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

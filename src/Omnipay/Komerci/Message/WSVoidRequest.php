<?php

namespace Omnipay\Komerci\Message;

/**
 * Komerci Authorize Request
 */
class WSVoidRequest extends \Omnipay\Common\Message\AbstractRequest
{
    use \Omnipay\Komerci\TraitRequest;

    public function getData()
    {
//        $this->validate('apikey', 'amount', 'transactionReference', 'numautor', 'username', 'password');

        $data = array(
            'Total' => sprintf("%.2F", round($this->getAmount() * 100) / 100),
            'Filiacao' => $this->getApiKey(),
            'NumCv' => $this->getTransactionReference(),
            'NumAutor' => $this->getNumAutor(),
            'Concentrador' => '',
            'Usr' => $this->getUsername(),
            'Pwd' => $this->getPassword()
        );

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->prepareSendData($data, 'VoidTransaction');
        return $this->response = new WSConfPreAuthResponse($this, $httpResponse->xml());
    }
}

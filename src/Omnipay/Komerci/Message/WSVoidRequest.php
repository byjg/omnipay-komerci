<?php

namespace Omnipay\Komerci\Message;

/**
 * Komerci Authorize Request
 */
class WSVoidRequest extends WSAbstractRequest
{
    use \Omnipay\Komerci\TraitRequest;

    public function getPreAuth()
    {
        return $this->getParameter('preauth');
    }

    public function setPreAuth($value)
    {
        return $this->setParameter('preauth', $value);
    }
    
    public function getData()
    {
        $this->validate('apikey', 'amount', 'transactionReference', 'numautor', 'username', 'password');

        if ($this->getPreAuth()) {
            return $this->getVoidPreAuthData();
        } else {
            return $this->getVoidPurchaseData();
        }
    }

    protected function getVoidPurchaseData()
    {
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

    protected function getVoidPreAuthData()
    {
        $data = $this->getVoidPurchaseData();

        if ($this->getTestMode()) {
            $data['Distribuidor'] = '';
        }
        $data['Data'] = date('Ymd');

        return $data;
    }

    public function sendData($data)
    {
        if ($this->getPreAuth()) {
            $httpResponse = $this->prepareSendData($data, 'VoidConfPreAuthorization');
        } else {
            $httpResponse = $this->prepareSendData($data, 'VoidTransaction');
        }
        return $this->response = new WSConfPreAuthResponse($this, $httpResponse->xml());
    }
}

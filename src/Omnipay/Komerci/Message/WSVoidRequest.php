<?php

namespace Omnipay\Komerci\Message;

/**
 * Komerci Authorize Request
 */
class WSVoidRequest extends WSAbstractRequest
{
    public function getPreAuth()
    {
        return $this->getParameter('preauth');
    }

    public function setPreAuth($value)
    {
        return $this->setParameter('preauth', $value);
    }

    public function getConfPreAuth()
    {
        return $this->getParameter('confpreauth');
    }

    public function setConfPreAuth($value)
    {
        return $this->setParameter('confpreauth', $value);
    }

    public function getData()
    {
        $this->validate('apikey', 'amount', 'transactionReference', 'numautor', 'username', 'password');

        if ($this->getPreAuth()) {
            return $this->getVoidPreAuthData();
        } else if ($this->getConfPreAuth()) {
            return $this->getVoidConfPreAuthData();
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

        $data['Distribuidor'] = '';
        $data['Data'] = $this->getFormattedDate();

        return $data;
    }

    protected function getVoidConfPreAuthData()
    {
        $data = $this->getVoidPurchaseData();

        $data['Parcelas'] = $this->getFormattedInstallments();
        $data['Data'] = $this->getFormattedDate();

        return $data;
    }

    public function sendData($data)
    {
        if ($this->getPreAuth()) {
            $httpResponse = $this->prepareSendData($data, 'VoidPreAuthorization');
        } else if ($this->getConfPreAuth()) {
            $httpResponse = $this->prepareSendData($data, 'VoidConfPreAuthorization');
        } else {
            $httpResponse = $this->prepareSendData($data, 'VoidTransaction');
        }
        return $this->response = new WSConfPreAuthResponse($this, $httpResponse->xml());
    }
}

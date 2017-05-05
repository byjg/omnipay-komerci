<?php

namespace Omnipay\Komerci;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Komerci Authorize Request
 */
abstract class BaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://ecommerce.userede.com.br/pos_virtual/wskomerci/cap.asmx/';
    protected $endpointTest = 'https://ecommerce.userede.com.br/pos_virtual/wskomerci/cap_teste.asmx/';

    public function getEndpoint($method)
    {
        return $this->getTestMode() ? $this->endpointTest . $method . "Tst" : $this->endpoint . $method;
    }

    public function getApiKey()
    {
        return $this->getParameter('apikey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apikey', $value);
    }

    public function getTestMode()
    {
        return $this->getParameter('testMode');
    }

    public function setTestMode($value)
    {
        return $this->setParameter('testMode', $value);
    }

    public function getUsername()
    {
        if ($this->getTestMode()) {
            return 'testews';
        }
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        if ($this->getTestMode()) {
            return 'testews';
        }
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getTransactionReference()
    {
        return $this->getParameter('transactionReference');
    }

    public function setTransactionReference($value)
    {
        return $this->setParameter('transactionReference', $value);
    }

    public function getNumAutor()
    {
        return $this->getParameter('numautor');
    }

    public function setNumAutor($value)
    {
        return $this->setParameter('numautor', $value);
    }

    public function getDate()
    {
        return $this->getParameter('date');
    }

    public function getFormattedDate()
    {
        return date('Ymd', strtotime($this->getParameter('date')));
    }

    public function setDate($value)
    {
        return $this->setParameter('date', $value);
    }

    /**
     *
     * @param type $data
     * @param string $method
     * @return \Omnipay\Common\Message\RequestInterface
     */
    protected function prepareSendData($data, $method)
    {
        if (!is_array($data)) {
            $data = array();
        }

        $httpResponse = $this->httpClient->post($this->getEndpoint($method), null, $data)->send();

        return $httpResponse;
    }

}

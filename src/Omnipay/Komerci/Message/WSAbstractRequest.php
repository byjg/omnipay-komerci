<?php

namespace Omnipay\Komerci\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Komerci Authorize Request
 */
abstract class WSAbstractRequest extends AbstractRequest
{

    protected $method = '';
    protected $endpoint = 'https://ecommerce.userede.com.br/pos_virtual/wskomerci/cap.asmx/';
    protected $endpointTest = 'https://ecommerce.userede.com.br/pos_virtual/wskomerci/cap_teste.asmx/';

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->endpointTest . $this->method . "Tst" : $this->endpoint . $this->method;
    }

    public function getApiKey()
    {
        return $this->getParameter('apikey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apikey', $value);
    }

    public function getInstallments()
    {
        return $this->getParameter('installments');
    }

    public function setInstallments($value)
    {
        return $this->setParameter('installments', $value);
    }

    protected function getFormattedInstallments()
    {
        $installments = '00';
        if ($this->getInstallments() > 1) {
            $installments = sprintf('%02d', $this->getInstallments());
        }

        return $installments;
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
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getNumCv()
    {
        return $this->getParameter('numcv');
    }

    public function setNumCv($value)
    {
        return $this->setParameter('numcv', $value);
    }

    public function getNumAutor()
    {
        return $this->getParameter('numautor');
    }

    public function setNumAutor($value)
    {
        return $this->setParameter('numautor', $value);
    }

    /**
     *
     * @param type $data
     * @return \Guzzle\Http
     */
    protected function prepareSendData($data)
    {
        if (!is_array($data)) {
            $data = array();
        }

        if ($this->getTestMode() || (isset($data["testMode"]) && $data["testMode"])) {
            $data['username'] = 'testews';
            $data['password'] = 'testews';
            unset($data['testMode']);
        }

        // First Try
        //$httpResponse = $this->httpClient->post($this->getEndpoint(), null, $data)->send();

        // Second Try
        $request = $this->httpClient->post($this->getEndpoint());
        $request->setHeader('Content-Type', 'application/x-www-form-urlencoded');
        $request->setHeader('Accept', '*/*');
        $request->setBody(http_build_query($data));
        $request->setHeader('User-Agent', 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');


        // Third Try
        $request = new \ByJG\Util\WebRequest($this->getEndpoint());
        print_r($request->post($data));

        $httpResponse = $request->send();

        return $httpResponse;
    }
}

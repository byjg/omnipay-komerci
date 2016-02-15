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

    public function getFiliacao()
    {
        return $this->getParameter('filiacao');
    }

    public function setFiliacao($value)
    {
        return $this->setParameter('filiacao', $value);
    }

    public function getParcelas()
    {
        return $this->getParameter('parcelas');
    }

    public function setParcelas($value)
    {
        return $this->setParameter('parcelas', $value);
    }

    public function getTransacao()
    {
        return $this->getParameter('transacao');
    }

    public function setTransacao($value)
    {
        return $this->setParameter('transacao', $value);
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
        if ($this->getTestMode() || (is_array($data) && isset($data["testMode"]) && $data["testMode"])) {
            if (!is_array($data)) {
                $data = array();
            }
            $data['username'] = 'testews';
            $data['password'] = 'testews';
        }
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $data)->send();

        return $httpResponse;
    }
}

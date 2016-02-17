<?php

namespace Omnipay\Komerci;

use Omnipay\Common\AbstractGateway;

/**
 * Komerci Gateway
 *
 * Quote: It is brazilian solution to accept payments with MasterCard, Visa and Diners Club International credit cards on the Internet.
 *
 * @link https://www.userede.com.br/
 * @link http://www.omnipay.com.br/gateways/komerci
 */
class Gateway extends AbstractGateway
{

    public function getName()
    {
        return 'Rede Komerci WebService';
    }

    public function getShortName()
    {
        return 'Komerci';
    }

    public function getDefaultParameters()
    {
        return array(
            'apikey' => '',
            'username' => '',
            'password' => '',
            'testMode' => false
        );
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
        if ($value) {
            $this->setUsername('testews');
            $this->setPassword('testews');
        }
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

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Komerci\Message\WSAuthorizeRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Komerci\Message\WSConfPreAuthRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Komerci\Message\WSPurchaseRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Komerci\Message\WSVoidRequest', $parameters);
    }

}

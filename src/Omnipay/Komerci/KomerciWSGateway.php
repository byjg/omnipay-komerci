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
class KomerciWSGateway extends AbstractGateway
{

    public function getName()
    {
        return 'Rede Komerci WebService';
    }

    public function getShortName()
    {
        return 'komerciws';
    }

    public function getDefaultParameters()
    {
        return array(
            'filiacao' => '',
            'transacao' => '04', // 04 - À vista, 06 - Parcelado Emissor, 08 - Parcelo Estabelecimento
            'parcelas' => '00', // 00 - À vista, 02 --> 09
            'username' => '',
            'password' => '',
            'testMode' => false
        );
    }

    public function getFiliacao()
    {
        return $this->getParameter('filiacao');
    }

    public function setFiliacao($value)
    {
        return $this->setParameter('filiacao', $value);
    }

    public function getTransacao()
    {
        return $this->getParameter('transacao');
    }

    public function setTransacao($value)
    {
        return $this->setParameter('transacao', $value);
    }

    public function getParcelas()
    {
        return $this->getParameter('parcelas');
    }

    public function setParcelas($value)
    {
        return $this->setParameter('parcelas', $value);
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
}

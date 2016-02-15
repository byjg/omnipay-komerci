<?php

namespace Omnipay\Komerci\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Komerci Authorize Response
 */
class WSAuthorizeResponse extends AbstractResponse
{

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        foreach ($data->children() as $childName => $childValue) {
            $this->data[strtoupper($childName)] = (string) $childValue;
        }
    }

    public function isSuccessful()
    {
        return ($this->getCode() == '0') && ($this->getNumCV() != '');
    }

    public function getCode()
    {
        return isset($this->data['CODRET']) ? $this->data['CODRET'] : '99';
    }

    public function getMessage()
    {
        return isset($this->data['MSGRET']) ? $this->data['MSGRET'] : 'Unknown';
    }

    public function getTransactionReference()
    {
        return $this->getNumCV();
    }

    public function getNumAutor()
    {
        return isset($this->data['NUMAUTOR']) ? $this->data['NUMAUTOR'] : '';
    }

    public function getNumCV()
    {
        return isset($this->data['NUMCV']) ? $this->data['NUMCV'] : '';
    }

    public function getNumPedido()
    {
        return isset($this->data['NUMPEDIDO']) ? $this->data['NUMPEDIDO'] : '';
    }

    public function getNumSqn()
    {
        return isset($this->data['NUMSQN']) ? $this->data['NUMSQN'] : '';
    }

    public function getNumAutent()
    {
        return isset($this->data['NUMAUTENT']) ? $this->data['NUMAUTENT'] : '';
    }

    public function getOrigemBin()
    {
        if (!isset($this->data['ORIGEM_BIN'])) {
            $this->data['ORIGEM_BIN'] = '';
        } elseif ($this->data['ORIGEM_BIN'] == 'BR') {
            $this->data['ORIGEM_BIN'] = 'BRA';
        }
        return $this->data['ORIGEM_BIN'];
    }

    public function getConfCodRet()
    {
        return isset($this->data['CONFCODRET']) ? $this->data['CONFCODRET'] : '';
    }

    public function getConfMsgRet()
    {
        return isset($this->data['CONFMSGRET']) ? $this->data['CONFMSGRET'] : '';
    }
}

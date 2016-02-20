<?php

namespace Omnipay\Komerci\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Komerci Authorize Response
 */
class WSConfPreAuthResponse extends AbstractResponse
{

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        foreach ($data->root->children() as $childName => $childValue) {
            $this->data[strtoupper($childName)] = (string) $childValue;
        }
    }

    public function isSuccessful()
    {
        return ($this->getCode() == '0');
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
        $result = [];
        preg_match('/@COMPR:(?<res>\d*?)\+*?V/', $this->getMessage(), $result);
        return isset($result['res']) ? $result['res'] : '';
    }

    public function getNumAutor()
    {
        $result = [];
        preg_match('/@AUTORIZACAO\+EMISSOR:\+(?<res>\d*?)\+*?@/', $this->getMessage(), $result);
        return isset($result['res']) ? $result['res'] : '';
    }

    public function getCard()
    {
        $result = [];
        preg_match('/@CARTAO:\+(?<res>\w*?)\+*?@/', $this->getMessage(), $result);
        return isset($result['res']) ? $result['res'] : '';
    }

    public function getAmount()
    {
        $result = [];
        preg_match('/\+VALOR:\+*?(?<int>[\d\.]*?),(?<dec>\d*?)@/', $this->getMessage(), $result);
        return isset($result['int']) ? floatval(str_replace('.', '', $result['int']) . '.' . $result['dec']) : null;
    }


}

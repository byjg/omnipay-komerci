<?php

namespace Omnipay\Komerci\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Komerci Authorize Response
 */
class WSConfPreAuthResponse extends AbstractResponse
{
	public function __construct(RequestInterface $request, $data)
	{
		$this->request = $request;

		foreach ($data->root->children() as $childName => $childValue)
		{
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

}

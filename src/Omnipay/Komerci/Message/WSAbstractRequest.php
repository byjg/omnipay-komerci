<?php

namespace Omnipay\Komerci\Message;

use Omnipay\Komerci\BaseRequest;

/**
 * Komerci Authorize Request
 */
abstract class WSAbstractRequest extends BaseRequest
{
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

}

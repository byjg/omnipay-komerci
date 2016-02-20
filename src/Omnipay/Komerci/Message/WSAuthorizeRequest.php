<?php

namespace Omnipay\Komerci\Message;

/**
 * Komerci Authorize Request
 */
class WSAuthorizeRequest extends WSAbstractRequest
{

    public function getData()
    {
        $this->validate('amount', 'apikey', 'card');

        $data = array(
            'Total' => sprintf("%.2F", round($this->getAmount() * 100) / 100),
            'Transacao' => '73', // 2-step authorization;
            'Parcelas' => $this->getFormattedInstallments(), // Docs said it needs to be empty but I am getting an error!
            'Filiacao' => $this->getApiKey(),
            'NumPedido' => $this->getTransactionId(),
            'Nrcartao' => $this->getCard()->getNumber(),
            'CVC2' => $this->getCard()->getCvv(),
            'Mes' => $this->getCard()->getExpiryMonth(),
            'Ano' => $this->getCard()->getExpiryYear(),
            'Portador' => $this->getCard()->getName(),
            'IATA' => '',
            'Distribuidor' => '',
            'Concentrador' => '',
            'TaxaEmbarque' => '',
            'Entrada' => '',
            'Numdoc1' => '',
            'Numdoc2' => '',
            'Numdoc3' => '',
            'Numdoc4' => '',
            'Pax1' => '',
            'Pax2' => '',
            'Pax3' => '',
            'Pax4' => '',
            'ConfTxn' => 'S',
            'Add_Data' => ''
        );

        // The test environment uses 'AddData' and the production environment uses 'Add_Data'
        if ($this->getTestMode()) {
            unset($data['Add_Data']);
            $data['AddData'] = '';
        }

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->prepareSendData($data, 'GetAuthorized');
        return $this->response = new WSAuthorizeResponse($this, $httpResponse->xml());
    }
}

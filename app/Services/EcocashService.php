<?php

namespace App\Services;

use App\Commands\ChargeEcocashNumberCommand;
use App\Models\EcocashPayment;
use Illuminate\Support\Facades\Http;

class EcocashService {

    /**
     * Create a new service instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->pin = config('ecocash.merchant.pin');
        $this->code = config('ecocash.merchant.code');
        $this->number = config('ecocash.merchant.number');
        $this->baseUri = config('ecocash.merchant.endpoint');
        $this->username = config('ecocash.merchant.username');
        $this->password = config('ecocash.merchant.password');
    }

    /**
     * Charge an ecocash number
     *
     * @param ChargeEcocashNumberCommand $command
     * @return void
     */
    public function charge(ChargeEcocashNumberCommand $command) : void
    {
        $payment = EcocashPayment::find($command->get('id'));
        return;

        $data = [
            "tranType" => "MER",
            "remarks" => "QuickTech",
            "merchantPin" => $this->pin,
            "merchantCode" => $this->code,
            "merchantNumber" => $this->number,
            "clientCorrelator" => $payment->client_correlator,
            "transactionOperationStatus" => "Charged",
            "endUserId" => $payment->ecocash_number,
            "notifyUrl" => 'some route on this server',
            "referenceCode" => $payment->reference_code,
            "paymentAmount" => [
                "charginginformation" => [
                    "currency" => "USD",
                    "description" => "QuickTech Online Payment",
                    "amount" => $payment->amount,
                ],
                "chargeMetaData" => [
                    "channel" => "WEB",
                    "onBeHalfOf" => "QuickTech",
                    "purchaseCategoryCode" => "Online Payment",
                ]
            ]
        ];

        Http::withBasicAuth($this->username, $this->password)->post($this->baseUri . 'transactions/amount', $data)->throw();
    }
}

<?php

namespace App\Services;

use App\Commands\ChargeEcocashNumberCommand;
use App\Models\EcocashPayment;
use Illuminate\Support\Facades\Http;

class EcocashService {

    /**
     * The EcoCash Merchant PIN code
     *
     * @var string
     */
    private string $merchantPin;

    /**
     * The EcoCash Merchant code
     *
     * @var string
     */
    private string $merchantCode;

    /**
     * The EcoCash Merchant number
     *
     * @var string
     */
    private string $merchantNumber;

    /**
     * The EcoCash Merchant API base URL
     *
     * @var string
     */
    private string $merchantApiBaseUrl;

    /**
     * The EcoCash Merchant API username
     *
     * @var string
     */
    private string $merchantApiUsername;

    /**
     * The EcoCash Merchant API password
     *
     * @var string
     */
    private string $merchantApiPassword;

    /**
     * Create a new service instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->merchantPin = config('services.ecocash.merchant.pin');
        $this->merchantCode = config('services.ecocash.merchant.code');
        $this->merchantNumber = config('services.ecocash.merchant.number');

        $this->merchantApiBaseUrl = config('services.ecocash.merchant.api.url');
        $this->merchantApiUsername = config('services.ecocash.merchant.api.username');
        $this->merchantApiPassword = config('services.ecocash.merchant.api.password');

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

        $data = [
            "tranType" => "MER",
            "remarks" => "QuickTech",
            "merchantPin" => $this->merchantPin,
            "merchantCode" => $this->merchantCode,
            "merchantNumber" => $this->merchantNumber,
            "clientCorrelator" => $payment->client_correlator,
            "transactionOperationStatus" => "Charged",
            "endUserId" => $payment->ecocash_number,
            "notifyUrl" => $payment->listener_endpoint,
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

        Http::withBasicAuth($this->merchantApiUsername, $this->merchantApiPassword)->post($this->merchantApiBaseUrl . 'transactions/amount', $data)->throw();
    }
}

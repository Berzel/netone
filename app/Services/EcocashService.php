<?php

namespace App\Services;

use App\Commands\ChargeEcocashNumberCommand;
use App\Commands\CheckEcocashPaymentCommand;
use App\Events\EcocashPaymentCompleted;
use App\Events\EcocashPaymentFailed;
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

        try {
            Http::withBasicAuth($this->merchantApiUsername, $this->merchantApiPassword)
                ->post($this->merchantApiBaseUrl . '/transactions/amount', $data)->throw();
        }

        catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Check the latest status of an ecocash payment
     *
     * @param CheckPaymentCommand $command
     * @return EcocashPayment
     */
    public function checkPaymentStatus(CheckEcocashPaymentCommand $command) : EcocashPayment
    {
        $payment = EcocashPayment::find($command->get('id'));

        if ($payment->isPaid()) {
            return $payment;
        }

        try {
            $txStatus = Http::withBasicAuth($this->merchantApiUsername, $this->merchantApiPassword)
                ->get($payment->poll_url)->throw()['transactionOperationStatus'];

            $statusHandlers = [
                'FAILED' => fn(): EcocashPayment => $this->onPaymentFailed($payment),
                'COMPLETED' => fn(): EcocashPayment => $this->onPaymentCompleted($payment),
                'PENDING SUBSCRIBER VALIDATION' => fn(): EcocashPayment => $payment
            ];

            return $statusHandlers[$txStatus]();
        }

        catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Handle failed ecocash payment
     *
     * @param EcocashPayment $payment
     * @return EcocashPayment
     */
    private function onPaymentFailed(EcocashPayment $payment) : EcocashPayment
    {
        $payment->status = 'failed';
        $payment->save();

        event(new EcocashPaymentFailed($payment));
        return $payment->fresh();
    }

    /**
     * Handle a successful ecocash payment
     *
     * @param EcocashPayment $payment
     * @return EcocashPayment
     */
    private function onPaymentCompleted(EcocashPayment $payment) : EcocashPayment
    {
        $payment->status = 'paid';
        $payment->save();

        event(new EcocashPaymentCompleted($payment));
        return $payment->fresh();
    }
}

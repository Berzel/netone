<?php

namespace App\Services;

use App\Commands\CreateEcoCashPaymentCommand;
use App\Commands\CreatePaymentCommand;
use App\Events\EcocashPaymentCreated;
use App\Models\EcocashPayment;
use App\Models\Payment;

class PaymentsService {

    /**
     * Create a payment
     *
     * @param CreatePaymentCommand $command
     * @return Payment
     */
    public function create(CreatePaymentCommand $command) : Payment
    {
        $paymentMethods = [
            'ecocash' => fn(): EcocashPayment => $this->createEcoCashPayment(new CreateEcoCashPaymentCommand($command->data())),
        ];

        try {
            return $paymentMethods[$command->get('payment_method')]();
        }

        catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Create an EcocashPayment
     *
     * @param CreateEcoCashPaymentCommand $command
     * @return EcocashPayment
     */
    private function createEcocashPayment(CreateEcoCashPaymentCommand $command) : EcocashPayment
    {
        $payment = EcocashPayment::create($command->data());
        event(new EcocashPaymentCreated($payment));
        return $payment;
    }
}

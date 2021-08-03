<?php

namespace App\Services;

use App\Commands\CreatePaymentCommand;
use App\Commands\RechargeCommand;
use App\Events\TopupInitiated;
use App\Models\Topup;
use Illuminate\Support\Facades\DB;

class RechargeService {

    /**
     * The payment service instance
     *
     * @var PaymentsService
     */
    private PaymentsService $paymentsService;

    /**
     * Crete a new service instance
     *
     * @param PaymentsService $paymentsService
     * @return void
     */
    public function __construct(PaymentsService $paymentsService)
    {
        $this->paymentsService = $paymentsService;
    }

    /**
     * Initiate a recharge
     *
     * @param RechargeCommand $command
     * @return Topup
     */
    public function recharge(RechargeCommand $command) : Topup
    {
        DB::beginTransaction();
        $topup = Topup::create($command->data());
        $topup->update(['amount' => number_format(1.05 * $topup->amount, 2, '.', '')]);
        $payment = $this->paymentsService->create(new CreatePaymentCommand($command->data()));
        $topup->update(['payment_id' => $payment->id, 'payment_method' => get_class($payment)]);
        $payment->update(['topup_id' => $topup->id]);
        event(new TopupInitiated($topup));
        DB::commit();

        return $topup;
    }
}

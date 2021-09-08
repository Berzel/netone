<?php

namespace App\Services;

use App\Commands\CreatePaymentCommand;
use App\Commands\RechargeCommand;
use App\Events\TopupInitiated;
use App\Models\Referral;
use App\Models\Topup;
use App\Models\WhatsappUser;
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
        $payment = $this->paymentsService->create(new CreatePaymentCommand($command->data()));
        $payment->update(['topup_id' => $topup->id]);
        $topup->update([
            'payment_id' => $payment->id,
            'payment_method' => get_class($payment),
            'amount' => $payment->amount
        ]);

        event(new TopupInitiated($topup));
        DB::commit();

        return $topup;
    }
}

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
        $refCode = array_key_exists('referral_code', $command->data()) ? $command->data()['referral_code'] : null;

        DB::beginTransaction();
        $topup = Topup::create($command->data());
        $payment = $this->paymentsService->create(new CreatePaymentCommand($command->data()));
        $payment->update(['topup_id' => $topup->id]);
        $topup->update([
            'payment_id' => $payment->id,
            'payment_method' => get_class($payment),
            'amount' => isset($refCode) ? 1.07 * $payment->amount : 1.05 * $payment->amount
        ]);

        if (isset($refCode)) {
            $referrer = WhatsappUser::whereReferralCode($command->get('referral_code'))->first();

            Referral::create([
                'topup_id' => $topup->id,
                'referrer_id' => $referrer->id
            ]);
        }

        event(new TopupInitiated($topup));
        DB::commit();

        return $topup;
    }
}

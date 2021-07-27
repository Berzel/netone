<?php

namespace App\Services;

use App\Commands\TopupAirtimeCommand;
use App\Models\Topup;
use Exception;
use Illuminate\Support\Facades\Http;

class NetoneAirtimeService {

    /**
     * The Netone API username
     *
     * @var string
     */
    private string $apiUsername;

    /**
     * The Netone API password
     *
     * @var string
     */
    private string $apiPassword;

    /**
     * The Netone API base url
     *
     * @var string
     */
    private string $apiBaseUrl;

    /**
     * Create a new service instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiBaseUrl = config('services.netone.api.url');
        $this->apiUsername = config('services.netone.api.username');
        $this->apiPassword = config('services.netone.api.password');
    }

    /**
     * Topup a netone number
     *
     * @param TopupAirtimeCommand $command
     * @return Topup
     */
    public function rechargePinless(TopupAirtimeCommand $command) : Topup
    {
        $topup = Topup::find($command->get('id'));

        try {
            $response = Http::withHeaders([
                'x-access-code' => $this->apiUsername,
                'x-access-password' => $this->apiPassword,
                'x-agent-reference' => $topup->reference_code
            ])->post($this->apiBaseUrl.'/agents/recharge-pinless', [
                'amount' => $topup->amount,
                'targetMobile' => $topup->netone_number,
                'CustomerSMS' => 'You have received $%AMOUNT% airtime top up from Magetsi. Your new balance is $%FINALBALANCE%.'
            ])->throw();

            if ($response['ReplyCode'] != 2) {
                throw new Exception($response['ReplyMessage'] ?? $response['ReplyMsg']);
            }

            $topup->status = 'completed';
            $topup->save();

            return $topup->fresh();
        }

        catch (\Throwable $th) {
            $topup->status = 'failed';
            $topup->save();
            throw $th;
        }
    }
}

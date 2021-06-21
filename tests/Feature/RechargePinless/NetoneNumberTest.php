<?php

namespace Tests\Feature\RechargePinless;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class NetoneNumberTest extends TestCase
{
    /**
     * The netone number to recharge should be supplied when recharging
     *
     * @return void
     */
    public function test_the_netone_number_is_required_when_recharging()
    {
        $response = $this->postJson('api/v1/topups', [
            'ecocash_number' => '0782632563',
            'payment_method' => 'ecocash',
            'amount' => '25'
        ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonPath('errors.netone_number.0', 'The netone number field is required.');
    }
}

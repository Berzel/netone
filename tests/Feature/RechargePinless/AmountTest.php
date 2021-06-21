<?php

namespace Tests\Feature\RechargePinless;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class AmountTest extends TestCase
{
    /**
     * The amount must be supplied when topping up a netone number
     *
     * @return void
     */
    public function test_the_amount_is_required_when_recharging()
    {
        $response = $this->postJson('api/v1/topups', [
            'netone_number' => '0717409643',
            'payment_method' => 'ecocash',
            'ecocash_number' => '0782632563'
        ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonPath('errors.amount.0', 'The amount field is required.');
    }
}

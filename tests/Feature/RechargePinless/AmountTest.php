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

    /**
     * The amount must be valid positive rational number with at most two decimals
     *
     * @return void
     */
    public function test_that_non_numeric_values_are_not_valid_amount()
    {
        $response = $this->postJson('api/v1/topups', [
            'netone_number' => '0717409643',
            'amount' => 'ten',
            'payment_method' => 'ecocash',
            'ecocash_number' => '0782632563'
        ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonPath('errors.amount.0', 'The amount must be a number.');
    }
}

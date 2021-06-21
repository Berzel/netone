<?php

namespace Tests\Feature\RechargePinless;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class EcoCashNumberTest extends TestCase
{
    /**
     * The ecocash number field should be supplied if the the chosen payment method is ecocash
     *
     * @return void
     */
    public function test_the_ecocash_number_is_required_when_recharging_using_ecocash()
    {
        $response = $this->postJson('api/v1/topups', [
            'netone_number' => '0717409643',
            'payment_method' => 'ecocash',
            'amount' => '25'
        ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonPath('errors.ecocash_number.0', 'The ecocash number field is required when payment method is ecocash.');
    }
}

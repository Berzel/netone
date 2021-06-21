<?php

namespace Tests\Feature\RechargePinless;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class PaymentMethodTest extends TestCase
{
    /**
     * The payment method field is required when recharging
     *
     * @return void
     */
    public function test_the_payment_method_is_required_when_recharging()
    {
        $response = $this->postJson('api/v1/topups', [
            'ecocash_number' => '0782632563',
            'netone_number' => '0717409643',
            'amount' => '25'
        ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonPath('errors.payment_method.0', 'The payment method field is required.');
    }

    /**
     * EcoCash is one of the payment methods we is accepted
     *
     * @return void
     */
    public function test_that_ecocash_is_a_valid_payment_method()
    {
        $response = $this->postJson('api/v1/topups', [
            'amount' => '25',
            'netone_number' => '0717409643',
            'payment_method' => 'ecocash',
            'ecocash_number' => '0782632563',
        ]);

        $response
            ->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Stripe is one of the payment methods we is accepted
     *
     * @return void
     */
    public function test_that_stripe_is_a_valid_payment_method()
    {
        $response = $this->postJson('api/v1/topups', [
            'amount' => '25',
            'netone_number' => '0717409643',
            'payment_method' => 'stripe',
        ]);

        $response
            ->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Only EcoCash & Stripe are accepted as payment methods.
     *
     * @return void
     */
    public function test_that_only_stripe_and_ecocash_are_valid_payment_methods()
    {
        $response = $this->postJson('api/v1/topups', [
            'amount' => '25',
            'netone_number' => '0717409643',
            'payment_method' => 'something else',
        ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonPath('errors.payment_method.0', 'The selected payment method is invalid.');
    }
}

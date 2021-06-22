<?php

namespace Tests\Feature\RechargePinless;

use App\Models\EcocashPayment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RechargeSuccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the entire buy airtime flow
     * Only fake HTTP requests to external APIs
     *
     * @return void
     */
    public function test_that_a_netone_number_is_recharged_successfully()
    {
        // Fake ecocash & netone http calls for this test

        $response = $this->postJson('api/v1/topups', [
            'amount' => '25',
            'netone_number' => '0717409643',
            'payment_method' => 'ecocash',
            'ecocash_number' => '0782632563',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('id', 1);

        $this->assertDatabaseHas('topups', [
            'amount' => 25,
            'status' => 'pending',
            'netone_number' => '0717409643',
            'payment_id' => 1,
            'payment_method' => EcocashPayment::class
        ]);

        $this->assertDatabaseHas('ecocash_payments', [
            'amount' => 25,
            'topup_id' => 1,
            'status' => 'pending',
            'ecocash_number' => '0782632563'
        ]);
    }
}

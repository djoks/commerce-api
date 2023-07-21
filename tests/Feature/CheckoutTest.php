<?php

namespace Tests\Feature;

use App\Models\EquipmentStock;

class CheckoutTest extends BaseTest
{
    private $url = '/checkout';

    /**
     * @test
     */
    public function successfulCheckout()
    {
        $this->authenticate();

        // Add Stock
        EquipmentStock::create([
            'equipment_id' => 1,
            'serial_number' => rand(100000, 999999) . 'ABC',
        ]);

        $response = $this->post($this->baseUrl . $this->url, [
            'client_id' => 1,
            'items' => [
                [
                    'equipment_id' => 1,
                    'quantity' => 1,
                ],
            ],
            'lease' => null,
            'payment_method' => 'cash',
        ]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function checkoutFailure()
    {
        $this->authenticate();
        $response = $this->post($this->baseUrl . $this->url, [], $this->headers);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }
}

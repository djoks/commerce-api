<?php

namespace Tests\Feature;

use App\Models\ProductStock;
use Illuminate\Support\Carbon;

class CheckoutTest extends BaseTest
{
    private $url = '/checkout';

    /**
     * @test
     */
    public function successfulCheckout()
    {
        $this->authenticate();

        $this->addStock();

        $response = $this->post($this->baseUrl . $this->url, [
            'customer_id' => 1,
            'billing_id' => 1,
            'items' => [
                [
                    'product_id' => 1,
                    'quantity' => 1,
                ],
            ],
            'payment_type_id' => 5,
            'shipping' => [
                'first_name' => 'Test',
                'last_name' => 'Monkey',
                'street_address' => 'No. 10 Downing Street',
                'city' => 'London',
                'state' => 'London',
                'country' => 'England'
            ]
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

    /**
     * Add stock to the database
     */
    protected function addStock()
    {
        ProductStock::create([
            'supplier_id' => 1,
            'product_id' => 1,
            'initial_quantity' => 10,
            'available_quantity' => 10,
            'purchase_date' => Carbon::now()->subMonths(3),
            'manufacture_date' => Carbon::now()->subMonths(6),
            'expiry_date' => Carbon::now()->addMonths(6),
            'notes' => 'Lorem ipsum dolor ait amet.'
        ]);
    }
}

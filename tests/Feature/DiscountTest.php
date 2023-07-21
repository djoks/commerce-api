<?php

namespace Tests\Feature;

class DiscountTest extends BaseTest
{
    private $url = '/discounts';

    /**
     * @test
     */
    public function creatingDiscountSuccessfully()
    {
        $this->authenticate();

        $response = $this->post($this->baseUrl . $this->url, [
            'name' => 'Silver Discount Package',
            'value' => 10,
            'unit' => 'percentage',
            'max_value' => 45000,
            'min_device_count' => 1,
            'max_device_count' => 5,
        ]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function creatingDiscountWithValidationFailure()
    {
        $this->authenticate();
        $response = $this->post($this->baseUrl . $this->url, [], $this->headers);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }

    /**
     * @test
     */
    public function getAllDiscounts()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '?page=1');
        $response->assertStatus(200)->assertJsonStructure($this->pagedJsonStructure);
    }

    /**
     * @test
     */
    public function getDiscountById()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '/1');
        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function deletingDiscountSuccessfully()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->url . '/2');
        $response->assertStatus($response->getStatusCode());
    }

    /**
     * @test
     */
    public function deletingDiscountWithFailure()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->url . '/800', [], $this->headers);
        $response->assertStatus(404);
    }
}

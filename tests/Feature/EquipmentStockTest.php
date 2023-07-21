<?php

namespace Tests\Feature;

class EquipmentStockTest extends BaseTest
{
    private $url = '/equipment-stock';

    /**
     * @test
     */
    public function creatingEquipmentStockSuccessfully()
    {
        $this->authenticate();

        $response = $this->post($this->baseUrl . $this->url, [
            'equipment_id' => 1,
            'serial_number' => rand(100000, 999999) . 'ABC',
        ]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function creatingEquipmentStockWithValidationFailure()
    {
        $this->authenticate();
        $response = $this->post($this->baseUrl . $this->url, [], $this->headers);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }

    /**
     * @test
     */
    public function getEquipmentStockById()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '/1');
        if ($response->getStatusCode() == 200) {
            $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
        } else {
            $response->assertStatus(404);
        }
    }
}

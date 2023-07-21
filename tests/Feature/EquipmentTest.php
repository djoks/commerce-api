<?php

namespace Tests\Feature;

class EquipmentTest extends BaseTest
{
    private $url = '/equipment';

    /**
     * @test
     */
    public function creatingEquipmentSuccessfully()
    {
        $this->authenticate();

        $response = $this->post($this->baseUrl . $this->url, [
            'supplier_id' => 1,
            'brand_id' => 1,
            'model_id' => 1,
            'type_id' => 1,
            'serial_number' => 'YEHEHEHEHEHEHE',
            'purchase_date' => '2023-01-01',
            'cost_price' => 10000,
            'selling_price' => 10000,
            'notes' => 'Nullable',
        ]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function creatingEquipmentWithValidationFailure()
    {
        $this->authenticate();
        $response = $this->post($this->baseUrl . $this->url, [], $this->headers);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }

    /**
     * @test
     */
    public function getAllEquipmentByBranch()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '?page=1');
        $response->assertStatus(200)->assertJsonStructure($this->pagedJsonStructure);
    }

    /**
     * @test
     */
    public function getEquipmentById()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '/1');
        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function deletingEquipmentSuccessfully()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->url . '/2');
        $response->assertStatus($response->getStatusCode());
    }

    /**
     * @test
     */
    public function deletingEquipmentWithFailure()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->url . '/8000', [], $this->headers);
        $response->assertStatus(404);
    }
}

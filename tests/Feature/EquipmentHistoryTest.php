<?php

namespace Tests\Feature;

class EquipmentHistoryTest extends BaseTest
{
    private $url = '/equipment-history';

    /**
     * @test
     */
    public function creatingHistorySuccessfully()
    {
        $this->authenticate();

        $response = $this->post($this->baseUrl . $this->url, [
            'equipment_id' => 1,
            'service_type' => 'repair',
            'technician' => 'Fixers Pro',
            'cost' => 10000,
            'parts_replaced' => [],
        ]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function creatingHistoryWithValidationFailure()
    {
        $this->authenticate();
        $response = $this->post($this->baseUrl . $this->url, [], $this->headers);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }

    /**
     * @test
     */
    public function getAllHistory()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '?page=1');
        $response->assertStatus(200)->assertJsonStructure($this->pagedJsonStructure);
    }

    /**
     * @test
     */
    public function getHistoryById()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '/1');

        if ($response->getStatusCode() == 200) {
            $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
        } else {
            $response->assertStatus(404);
        }
    }

    /**
     * @test
     */
    public function updatingHistorySuccessfully()
    {
        $this->authenticate();

        $response = $this->patch($this->baseUrl . $this->url . '/1', [
            'technician' => 'Kwaku Do All',
            'cost' => 10000,
            'parts_replaced' => [],
            'notes' => 'Fixers Pro could not fix it, so i transferred it to Kwaku Do All',
        ]);

        if ($response->getStatusCode() == 200) {
            $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
        } else {
            $response->assertStatus(404);
        }
    }

    /**
     * @test
     */
    public function updatingHistoryFailure()
    {
        $this->authenticate();

        $response = $this->patch($this->baseUrl . $this->url . '/4', [
            'technician' => 'Kwaku Do All',
            'cost' => 10000,
        ]);
        $response->assertStatus(404)->assertJsonStructure($this->jsonStructure);
    }
}

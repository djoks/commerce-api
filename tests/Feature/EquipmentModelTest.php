<?php

namespace Tests\Feature;

class EquipmentModelTest extends BaseTest
{
    private $url = '/equipment-models';

    /**
     * @test
     */
    public function creatingModelSuccessfully()
    {
        $this->authenticate();

        $response = $this->post($this->baseUrl . $this->url, [
            'brand_id' => 1,
            'name' => 'Test Model',
        ]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function creatingModelWithValidationFailure()
    {
        $this->authenticate();
        $response = $this->post($this->baseUrl . $this->url, [], $this->headers);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }

    /**
     * @test
     */
    public function getAllModels()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '?page=1');
        $response->assertStatus(200)->assertJsonStructure($this->pagedJsonStructure);
    }

    /**
     * @test
     */
    public function getModelById()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '/1');
        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function deletingModelSuccessfully()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->url . '/5');
        $response->assertStatus($response->getStatusCode());
    }

    /**
     * @test
     */
    public function deletingModelWithFailure()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->url . '/800', [], $this->headers);
        $response->assertStatus(404);
    }
}

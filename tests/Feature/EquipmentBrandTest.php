<?php

namespace Tests\Feature;

class EquipmentBrandTest extends BaseTest
{
    private $url = '/equipment-brands';

    /**
     * @test
     */
    public function creatingBrandSuccessfully()
    {
        $this->authenticate();

        $response = $this->post($this->baseUrl . $this->url, [
            'name' => 'Test Brand',
        ]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function creatingBrandWithValidationFailure()
    {
        $this->authenticate();
        $response = $this->post($this->baseUrl . $this->url, [], $this->headers);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }

    /**
     * @test
     */
    public function getAllBrands()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '?page=1');
        $response->assertStatus(200)->assertJsonStructure($this->pagedJsonStructure);
    }

    /**
     * @test
     */
    public function getBrandById()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '/1');
        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function deletingBrandSuccessfully()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->url . '/35');
        $response->assertStatus($response->getStatusCode());
    }

    /**
     * @test
     */
    public function deletingBrandWithFailure()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->url . '/800', [], $this->headers);
        $response->assertStatus(404);
    }
}

<?php

namespace Tests\Feature;

class SupplierTest extends BaseTest
{
    private $url = '/admin/suppliers';

    /**
     * @test
     */
    public function creatingSupplierSuccessfully()
    {
        $this->authenticate();

        $response = $this->post($this->baseUrl . $this->url, [
            'name' => 'Test Supplier',
            'phone' => '0000000001',
            'email' => 'mail@mail.com',
            'address' => 'Address',
            'contact_person_name' => 'James Olson',
            'contact_person_phone' => '0000000001',
            'contact_person_email' => 'mail@mail.com',
        ]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function creatingSupplierWithValidationFailure()
    {
        $this->authenticate();
        $response = $this->post($this->baseUrl . $this->url, [], $this->headers);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }

    /**
     * @test
     */
    public function getBranchSupplier()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '?page=1');
        $response->assertStatus(200)->assertJsonStructure($this->pagedJsonStructure);
    }

    /**
     * @test
     */
    public function getSupplierById()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '/1');
        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function deletingSupplierSuccessfully()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->url . '/2');
        $response->assertStatus($response->getStatusCode());
    }

    /**
     * @test
     */
    public function deletingSupplierWithFailure()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->url . '/1', [], $this->headers);
        $response->assertStatus(400);
    }
}

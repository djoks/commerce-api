<?php

namespace Tests\Feature;

class ClientTest extends BaseTest
{
    private $url = '/clients';

    /**
     * @test
     */
    public function creatingClientSuccessfully()
    {
        $this->authenticate();

        $response = $this->post($this->baseUrl . $this->url, [
            'name' => 'Test Client',
            'branch_id' => 1,
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
    public function creatingClientWithValidationFailure()
    {
        $this->authenticate();
        $response = $this->post($this->baseUrl . $this->url, [], $this->headers);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }

    /**
     * @test
     */
    public function getBranchClient()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '?page=1');
        $response->assertStatus(200)->assertJsonStructure($this->pagedJsonStructure);
    }

    /**
     * @test
     */
    public function getClientById()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '/1');
        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function deletingClientSuccessfully()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->url . '/2');

        $response->assertStatus($response->getStatusCode());
    }

    /**
     * @test
     */
    public function deletingClientWithFailure()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->url . '/8000', [], $this->headers);
        $response->assertStatus(404);
    }
}

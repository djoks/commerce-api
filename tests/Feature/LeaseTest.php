<?php

namespace Tests\Feature;

class LeaseTest extends BaseTest
{
    private $url = '/leases';

    /**
     * @test
     */
    public function updatingLeaseStatusSuccessfully()
    {
        $this->authenticate();

        $response = $this->patch($this->baseUrl . $this->url . '/1', [
            'status' => 'Active',
        ]);

        if ($response->getStatusCode() == 200 || $response->getStatusCode() == 422) {
            $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
        } else {
            $response->assertStatus(404);
        }
    }

    /**
     * @test
     */
    public function updatingLeaseStatusWithFailure()
    {
        $this->authenticate();
        $response = $this->patch($this->baseUrl . $this->url . '/1', [], $this->headers);
        if ($response->getStatusCode() == 200 || $response->getStatusCode() == 422) {
            $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
        } else {
            $response->assertStatus(404);
        }
    }

    /**
     * @test
     */
    public function getAllLeases()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '?page=1');
        $response->assertStatus(200)->assertJsonStructure($this->pagedJsonStructure);
    }

    /**
     * @test
     */
    public function getLeaseById()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '/1');
        if ($response->getStatusCode() == 200 || $response->getStatusCode() == 422) {
            $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
        } else {
            $response->assertStatus(404);
        }
    }
}

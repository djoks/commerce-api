<?php

namespace Tests\Feature;

class LogoutTest extends BaseTest
{
    private $url = '/auth/logout';

    /**
     * @test
     */
    public function successfulResponse()
    {
        $this->authenticate();

        $response = $this->post($this->baseUrl . $this->url, []);
        $response->assertOk()->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function failedResponse()
    {
        $response = $this->post($this->baseUrl . $this->url, [], $this->headers);
        $response->assertStatus(401)->assertJsonStructure(['message']);
    }
}

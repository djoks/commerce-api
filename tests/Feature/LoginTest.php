<?php

namespace Tests\Feature;

class LoginTest extends BaseTest
{
    private $url = '/auth/login';

    /**
     * @test
     */
    public function successfulResponse()
    {
        $response = $this->post($this->baseUrl . $this->url, [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function failedResponse()
    {
        $response = $this->post($this->baseUrl . $this->url, [
            'email' => 'test@example.com',
            'password' => 'passwor',
        ]);

        $response->assertStatus(401)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function failedValidationResponse()
    {
        $response = $this->withHeaders($this->headers)
            ->post($this->baseUrl . $this->url, []);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }
}

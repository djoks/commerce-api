<?php

namespace Tests\Feature;

class UserTest extends BaseTest
{
    private $url = '/admin/users';

    /**
     * @test
     */
    public function creatingUserSuccessfully()
    {
        $this->authenticate();

        $response = $this->post($this->baseUrl . $this->url, [
            'name' => 'Test User',
            'phone' => '0000000000',
            'email' => 'mail@mail.com',
            'role' => 'developer',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function creatingUserWithValidationFailure()
    {
        $this->authenticate();
        $response = $this->post($this->baseUrl . $this->url, [], $this->headers);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }

    /**
     * @test
     */
    public function getUsers()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '?page=1');
        $response->assertStatus(200)->assertJsonStructure($this->pagedJsonStructure);
    }

    /**
     * @test
     */
    public function getUserById()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '/1');
        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function updatingUserPasswordSuccessfully()
    {
        $this->authenticate();
        $response = $this->patch($this->baseUrl . $this->url . '/update/password', [
            'current_password' => 'password',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function updatingUserPasswordWithValidationFailure()
    {
        $this->authenticate();
        $response = $this->patch($this->baseUrl . $this->url . '/update/password', [], $this->headers);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }

    /**
     * @test
     */
    public function updatingUserRoleSuccessfully()
    {
        $this->authenticate();
        $response = $this->patch($this->baseUrl . $this->url . '/1/role', [
            'role' => 'customer',
        ]);
        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function updatingUserRoleWithValidationFailure()
    {
        $this->authenticate();
        $response = $this->patch($this->baseUrl . $this->url . '/1/role', [], $this->headers);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }

    /**
     * @test
     */
    public function activatingOrDeactivatingUserSuccessfully()
    {
        $this->authenticate();
        $response = $this->patch($this->baseUrl . $this->url . '/1/status', [
            'active' => true,
        ]);
        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function deletingUserSuccessfully()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->url . '/2');
        $response->assertStatus($response->getStatusCode());
    }

    /**
     * @test
     */
    public function deletingUserWithFailure()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->url . '/800', [], $this->headers);
        $response->assertStatus(404);
    }
}

<?php

namespace Tests\Feature;

class RoleTest extends BaseTest
{
    private $url = '/roles';

    /**
     * @test
     */
    public function getAllRoles()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url);
        $response->assertStatus(200)->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function getSpecificUserRoles()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '?user_id=1');
        $response->assertStatus(200)->assertJsonStructure(['data']);
    }
}

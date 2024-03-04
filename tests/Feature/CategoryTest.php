<?php

namespace Tests\Feature;

class CategoryTest extends BaseTest
{
    private $adminUrl = '/admin/categories';
    private $url = '/categories';

    /**
     * @test
     */
    public function creatingCategorySuccessfully()
    {
        $this->authenticate();

        $response = $this->post($this->baseUrl . $this->adminUrl, [
            'name' => 'Test Category',
        ]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function creatingCategoryWithValidationFailure()
    {
        $this->authenticate();
        $response = $this->post($this->baseUrl . $this->adminUrl, [], $this->headers);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }

    /**
     * @test
     */
    public function getAllCategories()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '?page=1');
        $response->assertStatus(200)->assertJsonStructure($this->pagedJsonStructure);
    }

    /**
     * @test
     */
    public function getCategoryBySlug()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '/electronics');
        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function deletingCategorySuccessfully()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->adminUrl . '/3');
        $response->assertStatus($response->getStatusCode());
    }

    /**
     * @test
     */
    public function deletingCategoryWithFailure()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->adminUrl . '/999', [], $this->headers);
        $response->assertStatus(404);
    }
}

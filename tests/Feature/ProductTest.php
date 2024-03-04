<?php

namespace Tests\Feature;

class ProductTest extends BaseTest
{
    private $adminUrl = '/admin/products';
    private $url = '/products';

    /**
     * @test
     */
    public function creatingProductSuccessfully()
    {
        $this->authenticate();

        $response = $this->post($this->baseUrl . $this->adminUrl, [
            'category_id' => 1,
            'name' => 'Test',
            'barcode' => '1293230300',
            'cost_price' => 10.0,
            'selling_price' => 15.0,
            'notes' => 'lorem ipsum dolor sit amet.'
        ]);

        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function creatingProductWithValidationFailure()
    {
        $this->authenticate();
        $response = $this->post($this->baseUrl . $this->adminUrl, [], $this->headers);
        $response->assertStatus(422)->assertJsonStructure($this->failedValidationJsonStructure);
    }

    /**
     * @test
     */
    public function getProductBySlug()
    {
        $this->authenticate();
        $response = $this->get($this->baseUrl . $this->url . '/test-product');
        $response->assertStatus(200)->assertJsonStructure($this->jsonStructure);
    }

    /**
     * @test
     */
    public function deletingProductSuccessfully()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->adminUrl . '/2');
        $response->assertStatus($response->getStatusCode());
    }

    /**
     * @test
     */
    public function deletingProductWithFailure()
    {
        $this->authenticate();
        $response = $this->delete($this->baseUrl . $this->adminUrl . '/8000', [], $this->headers);
        $response->assertStatus(404);
    }
}

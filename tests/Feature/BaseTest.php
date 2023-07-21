<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BaseTest extends TestCase
{
    use DatabaseTransactions;

    protected $baseUrl = '/api/v1';

    protected $jsonStructure = [
        'message',
        'data',
    ];

    protected $pagedJsonStructure = [
        'data',
        'links',
        'meta',
    ];

    protected $failedValidationJsonStructure = [
        'message',
        'errors',
    ];

    protected $headers = [
        'Accept' => 'application/json',
    ];

    public function authenticate()
    {
        $user = User::first();
        Sanctum::actingAs($user);
    }
}

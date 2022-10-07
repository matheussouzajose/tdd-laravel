<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Repository\Eloquent\Providers\UserProvider;
use App\Repository\Exceptions\NotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use UserProvider;

    protected string $endpoint = '/api/users';

    /**
     * @dataProvider dataProviderPagination
     */
    public function test_paginate(
        int $total,
        int $page = 1,
        int $totalPage = 15
    )
    {
        User::factory()->count($total)->create();

        $response = $this->getJson("{$this->endpoint}?page={$page}");
        $response->assertOk();
        $response->assertJsonCount($totalPage, 'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
            ],
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email'
                ]
            ]
        ]);

        $response->assertJsonFragment([
            'total' => $total,
            'current_page' => $page
        ]);
    }

    /**
     * @dataProvider dataProviderCreateUser
     */
    public function test_create(
        array $payload,
        int   $statusCode,
        array $strucutureResponse
    )
    {
        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus($statusCode);
        $response->assertJsonStructure($strucutureResponse);
    }

    public function test_find()
    {
        $user = User::factory()->create();

        $user = $this->getJson("{$this->endpoint}/{$user->email}");
        $user->assertStatus(Response::HTTP_OK);
        $user->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email'
            ]
        ]);
    }

    public function test_find_not_found()
    {
        $user = $this->getJson("{$this->endpoint}/fake_email");
        $user->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider dataProviderUpdateUser
     */
    public function test_update(
        array $payload,
        int $statusCode,
        array $strucutureResponse
    )
    {
        $user = User::factory()->create();

        $response = $this->putJson("{$this->endpoint}/{$user->email}", $payload);
        $response->assertStatus($statusCode);
        $response->assertJsonStructure($strucutureResponse);
    }

    public function test_update_not_found()
    {
        $user = $this->putJson("{$this->endpoint}/fake_email", [
            'name' => 'Matheus S. Jose'
        ]);
        $user->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete()
    {
        $user = $this->deleteJson("{$this->endpoint}/fake_email");
        $user->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete_not_found()
    {
        $user = User::factory()->create();

        $user = $this->deleteJson("{$this->endpoint}/{$user->email}");
        $user->assertNoContent();
    }
}

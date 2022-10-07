<?php

namespace Tests\Feature\App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Eloquent\UserRepository;
use App\Repository\Exceptions\NotFoundException;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    protected UserRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new UserRepository(new User());
        parent::setUp();
    }

    public function test_implements_interface()
    {
        $this->assertInstanceOf(
            UserRepositoryInterface::class,
            $this->repository
        );
    }

    public function test_find_all_empty()
    {
        $response = $this->repository->findAll();

        $this->assertIsArray($response);
        $this->assertCount(0, $response);
    }

    public function test_find_all()
    {
        User::factory()->count(10)->create();

        $response = $this->repository->findAll();

        $this->assertCount(10, $response);
    }

    public function test_store()
    {
        $data = [
            'name' => 'Matheus S. Jose',
            'email' => 'matheus@gmail.com',
            'password' => bcrypt('123456')
        ];

        $response = $this->repository->create($data);

        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'email' => 'matheus@gmail.com'
        ]);
    }

    public function test_create_exception()
    {

        $this->expectException(QueryException::class);

        $data = [
            'email' => 'matheus@gmail.com',
            'password' => bcrypt('123456')
        ];

        $this->repository->create($data);
    }

    public function test_update()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'new name'
        ];

        $response = $this->repository->update($user->email, $data);

        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'name' => 'new name'
        ]);
    }

    public function test_delete()
    {
        $user = User::factory()->create();

        $deleted = $this->repository->delete($user->email);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('users', [
            'email' => $user->email
        ]);
    }

    public function test_delete_not_found()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->delete('fake_email');

//        try {
//            $this->repository->delete('fake_email');
//            $this->assertTrue(false);
//        } catch (\Exception $th) {
//            $this->assertInstanceOf(\Exception::class, $th);
//        }
    }

    public function test_find()
    {
        $user = User::factory()->create();

        $response = $this->repository->find($user->email);
        $this->assertIsObject($response);
    }

    public function test_find_not_found()
    {
        $this->expectException(NotFoundException::class);
        $this->repository->find('fake_email');
    }
}

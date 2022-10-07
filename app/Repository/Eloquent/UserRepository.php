<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Contracts\PaginationInterface;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Exceptions\NotFoundException;
use App\Repository\Presenters\PaginationPresenter;

class UserRepository implements UserRepositoryInterface
{
    /** @var User */
    protected User $model;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @throws NotFoundException
     */
    public function find(string $email): ?object
    {
        if (!$user = $this->model->where('email', $email)->first()) {
            throw new NotFoundException('User Not Found');
        }
        return $user;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->model->get()->toArray();
    }

    /**
     * @param int $page
     * @return PaginationInterface
     */
    public function paginate(int $page = 1): PaginationInterface
    {
        return new PaginationPresenter($this->model->paginate());
    }

    /**
     * @param array $data
     * @return object
     */
    public function create(array $data): object
    {
        $data['password'] = bcrypt($data['password']);
        return $this->model->create($data);
    }

    /**
     * @throws NotFoundException
     */
    public function update(string $email, array $data): object
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user = $this->find($email);
        $user->update($data);
        $user->refresh();
        return $user;
    }

    /**
     * @throws NotFoundException
     */
    public function delete(string $email): bool
    {
        return $this->find($email)->delete();
    }
}

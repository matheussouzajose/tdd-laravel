<?php

namespace App\Repository\Contracts;

interface UserRepositoryInterface
{
    /**
     * @param string $email
     * @return object|null
     */
    public function find(string $email): ?object;

    /**
     * @param int $page
     * @return PaginationInterface
     */
    public function paginate(int $page = 1): PaginationInterface;

    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param array $data
     * @return object
     */
    public function create(array $data): object;

    /**
     * @param string $email
     * @param array $data
     * @return object
     */
    public function update(string $email, array $data): object;

    /**
     * @param string $email
     * @return bool
     */
    public function delete(string $email): bool;
}

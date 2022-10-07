<?php

namespace App\Repository\Contracts;

interface PaginationInterface
{
    /**
     * @return array
     */
    public function items(): array;

    /**
     * @return int
     */
    public function total(): int;

    /**
     * @return int
     */
    public function currentPage(): int;

    /**
     * @return int
     */
    public function perPage(): int;

    /**
     * @return int
     */
    public function firstPage(): int;

    /**
     * @return int
     */
    public function lastPage(): int;
}

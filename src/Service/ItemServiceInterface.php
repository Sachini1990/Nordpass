<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Item;

interface ItemServiceInterface
{
    /**
     * @param User $user
     * @return array
     */
    public function getItemsByUser(User $user): array;

    /**
     * @param User $user
     * @param string $data
     */
    public function create(User $user, string $data): void;

    /**
     * @param int $id
     * @return Item|null
     */
    public function getItemById(int $id): ?Item;

    /**
     * @param int $id
     * @param string $data
     * @return bool
     */
    public function update(int $id, string $data): bool ;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool ;
}

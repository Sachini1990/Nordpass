<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\User;

interface ItemRepositoryInterface
{
    /**
     * @param User $user
     * @return array|null
     */
    public function getItemsByUser(User $user): ?array ;

    /**
     * @param User $user
     * @param string $data
     */
    public function create(User $user, string $data): void;

    /**
     * @param $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return mixed
     */
    public function find($id, $lockMode = null, $lockVersion = null);

    /**
     * @param Item $item
     * @param $data
     */
    public function update(Item $item, $data): void ;

    /**
     * @param Item $item
     */
    public function delete(Item $item): void ;
}

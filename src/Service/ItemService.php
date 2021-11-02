<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\User;
use App\Repository\ItemRepositoryInterface;

class ItemService implements ItemServiceInterface
{
    private $itemRepository;

    /**
     * ItemService constructor.
     * @param ItemRepositoryInterface $itemRepository
     */
    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param User $user
     * @return array
     */
    public function getItemsByUser(User $user): array
    {
        $items = $this->itemRepository->getItemsByUser($user);

        $allItems = [];

        foreach ($items as $item) {
            $oneItem['id'] = $item->getId();
            $oneItem['data'] = $item->getData();
            $oneItem['created_at'] = $item->getCreatedAt();
            $oneItem['updated_at'] = $item->getUpdatedAt();
            $allItems[] = $oneItem;
        }

        return $allItems;
    }

    /**
     * @param int $id
     * @return Item|null
     */
    public function getItemById(int $id): ?Item
    {
        return $this->itemRepository->find($id);
    }

    /**
     * @param User $user
     * @param string $data
     */
    public function create(User $user, string $data): void
    {
        $this->itemRepository->create($user, $data);
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     */
    public function update(int $id, string $data): bool
    {
        $item = $this->itemRepository->find($id);

        if ($item === null) {

            return false;
        }

        $this->itemRepository->update($item, $data);

        return true;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $item = $this->itemRepository->find($id);

        if ($item === null) {

            return false;
        }

        $this->itemRepository->delete($item);

        return true;
    }
} 
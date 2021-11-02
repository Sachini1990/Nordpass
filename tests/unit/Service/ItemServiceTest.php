<?php

namespace App\Tests\Unit;

use App\Entity\Item;
use App\Entity\User;
use App\Service\ItemService;
use PHPUnit\Framework\TestCase;
use App\Repository\ItemRepositoryInterface;

class ItemServiceTest extends TestCase
{
    /**
     * @var ItemService
     */
    private $itemService;

    public function testGetItemsByUser(): void
    {
        /** @var User */
        $user = $this->createMock(User::class);

        $itemRepository = $this->createMock(ItemRepositoryInterface::class);
        $itemRepository->expects($this->once())
            ->method('getItemsByUser')
            ->with($user)
            ->willReturn([]);

        $this->itemService = new ItemService($itemRepository);

        $this->itemService->getItemsByUser($user);
    }

    public function testCreate(): void
    {
        /** @var User */
        $user = $this->createMock(User::class);
        $data = 'secret data';

        $itemRepository = $this->createMock(ItemRepositoryInterface::class);
        $itemRepository->expects($this->once())
            ->method('create')
            ->with($user, $data)
            ->will($this->returnCallback(function() {return;}));

        $this->itemService = new ItemService($itemRepository);

        $this->itemService->create($user, $data);
    }

    public function testUpdateNotExistingItem(): void
    {
        $data = 'secret data';
        $id = 11;

        $itemRepository = $this->createMock(ItemRepositoryInterface::class);
        $itemRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->itemService = new ItemService($itemRepository);

        $this->itemService->update($id, $data);
    }

    public function testUpdate(): void
    {
        $data = 'secret data';
        $id = 1;

        $itemRepository = $this->createMock(ItemRepositoryInterface::class);
        $item = $this->createMock(Item::class);

        $itemRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($item);

        $itemRepository->expects($this->once())
            ->method('update')
            ->with($item, $data)
            ->will($this->returnCallback(function() {return;}));

        $this->itemService = new ItemService($itemRepository);

        $this->itemService->update(1, $data);
    }

    public function testDeleteNotExistingItem(): void
    {
        $id = 99999999999;

        $itemRepository = $this->createMock(ItemRepositoryInterface::class);
        $itemRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->itemService = new ItemService($itemRepository);

        $this->itemService->delete($id);
    }

    public function testDelete(): void
    {
        $id = 1;

        $itemRepository = $this->createMock(ItemRepositoryInterface::class);
        $item = $this->createMock(Item::class);

        $itemRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($item);

        $itemRepository->expects($this->once())
            ->method('delete')
            ->with($item)
            ->will($this->returnCallback(function() {return;}));

        $this->itemService = new ItemService($itemRepository);

        $this->itemService->delete(1);
    }
}

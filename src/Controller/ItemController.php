<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ItemServiceInterface;

class ItemController extends AbstractController
{
    /**
     * @var ItemServiceInterface
     */
    private $itemService;

    /**
     * ItemController constructor.
     * @param ItemServiceInterface $itemService
     */
    public function __construct(ItemServiceInterface $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * @Route("/item", name="item_list", methods={"GET"})
     */
    public function list(): JsonResponse
    {
        $allItems = $this->itemService->getItemsByUser($this->getUser());

        return $this->json($allItems);
    }

    /**
     * @Route("/item", name="item_create", methods={"POST"})
     * @param CreateItemRequest $request
     * @return JsonResponse
     */
    public function create(CreateItemRequest $request)
    {
        if (!$request->isValid()) {

            return $this->json(['error' => 'No data parameter']);
        }

        $data = $request->getData();

        $this->itemService->create($this->getUser(), $data);

        return $this->json([]);
    }

    /**
     * @Route("/item", name="item_update", methods={"PUT"})
     * @param UpdateItemRequest $request
     * @return JsonResponse
     */
    public function update(UpdateItemRequest $request)
    {
        if (!$request->isValid()) {

            return $this->json(['error' => 'Invalid parameters. Provide Id and Data']);
        }

        $id = $request->getId();
        $data = $request->getData();

        if(!$this->itemService->update($id, $data)) {

            return $this->json(['error' => 'No item'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([]);
    }

    /**
     * @Route("/item/{id}", name="items_delete", methods={"DELETE"})
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id)
    {
        if(!$this->itemService->delete($id)) {

            return $this->json(['error' => 'No item'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([]);
    }
}

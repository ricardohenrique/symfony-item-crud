<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Item;
use App\Service\ItemService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends AbstractController
{
    /**
     * @var ItemService
     */
    public $itemService;

    /**
     * Constructor class
     *
     * @access public
     * @param ItemService $itemService
     */
    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * @Route("/item", name="item_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list(): JsonResponse
    {
        $items = $this->itemService->getAll($this->getUser());
        return $this->json($items);
    }

    /**
     * @Route("/item", name="item_create", methods={"POST"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $data = $request->get('data');

        if (empty($data)) {
            return $this->json(['error' => 'No data parameter']);
        }

        $this->itemService->create($this->getUser(), $data);

        return $this->json([]);
    }

    /**
     * @Route("/item/{id}", name="item_update", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) {
            return $this->json(['error' => 'No data parameter']);
        }

        $return = $this->itemService->update(array_merge($data, ['id' => $id]));
        if ($return === null) {
            return $this->json(['error' => 'No item found'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([]);
    }

    /**
     * @Route("/item/{id}", name="items_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id)
    {
        if (empty($id)) {
            return $this->json(['error' => 'No data parameter'], Response::HTTP_BAD_REQUEST);
        }

        $return = $this->itemService->delete($id);
        if ($return === null) {
            return $this->json(['error' => 'No item'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([]);
    }
}

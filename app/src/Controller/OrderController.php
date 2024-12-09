<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Attributes as OA;
use App\Repository\OrdersRepository;


class OrderController extends AbstractController
{
    private OrdersRepository $ordersRepository;



    public function __construct(OrdersRepository $ordersRepository, )
    {
        $this->ordersRepository = $ordersRepository;
    }


    #[Route('/api/orders-count', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the count of orders grouped by date.',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'groupBy', type: 'string', example: 'day'),
                new OA\Property(property: 'totalPages', type: 'intager', example: 1),
                new OA\Property(property: 'page', type: 'intager', example: 1),
                new OA\Property(property: 'limit', type: 'intager', example: 10),


                new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'groupDate', type: 'string', example: '2024-11-22'),
                        new OA\Property(property: 'orderCount', type: 'integer', example: 15),
                    ]
                )),
                new OA\Property(property: 'total', type: 'integer', example: 45),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Invalid parameters provided.',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: false),
                new OA\Property(property: 'message', type: 'string', example: 'Invalid groupBy parameter.'),
            ]
        )
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'Текущая страница набора результатов.',
        example: 1,
        schema: new OA\Schema(type: 'integer', minimum: 1)
    )]
    #[OA\Parameter(
        name: 'limit',
        in: 'query',
        description: 'Количество элементов на странице.',
        example: 10,
        schema: new OA\Schema(type: 'integer', minimum: 1)
    )]
    #[OA\Parameter(
        name: 'groupBy',
        in: 'query',
        description: 'Критерии группировки заказов по дате (например, месяц, год или день).',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            enum: ['month', 'year', 'day'],
            example: 'month'
        )
    )]
    #[OA\Tag(name: 'Orders')]
    #[OA\Get(
        summary: 'Эндпоинт №2',
        description: 'при GET запросе с пагинацией (страница, количество на странице) и группировкой. Отдать json с количеством заказов по месяцам, годам, дням. В ответе данные о станице, количестве на странице, количестве страниц итд и основная нагрузка - группирирующее значение и количество;'
    )]
    public function getOrders(Request $request): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);
        $groupBy = $request->query->get('groupBy', 'month');
        if (!in_array($groupBy, ['month', 'year', 'day'])) {
            return new JsonResponse(['success'=>false, 'message' => 'Параметр "groupBy" может быть только: month|year|day'], 400);
        }
        try {
            $orders = $this->ordersRepository->groupByDate($groupBy, $page, $limit);
            return new JsonResponse([
                'groupBy' => $groupBy,
                'totalPages' => $orders['totalPages'],
                'page' => $page,
                'limit' => $limit,
                'data' => $orders['results']
            ]);
        }   catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
}
    }

}

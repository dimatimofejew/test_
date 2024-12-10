<?php

namespace App\Controller;


use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Attributes as OA;
use App\Repository\OrdersRepository;
use Manticoresearch\Client;
use Manticoresearch\Search;

class OrderController extends AbstractController
{
    private OrdersRepository $ordersRepository;

    public Client $manticoreClient;
    public Search $searchService;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(OrdersRepository $ordersRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->ordersRepository = $ordersRepository;
        $this->manticoreClient = new Client([
            'host' => 'sphinx',
            'port' => 9308, // Порт Manticore Search
        ]);
        $this->searchService = new Search($this->manticoreClient);
        $this->urlGenerator = $urlGenerator;
    }


    #[Route('/api/orders-count', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the count of orders grouped by date.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'groupBy', type: 'string', example: 'day'),
                new OA\Property(property: 'totalPages', type: 'intager', example: 1),
                new OA\Property(property: 'page', type: 'intager', example: 1),
                new OA\Property(property: 'limit', type: 'intager', example: 10),


                new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'groupDate', type: 'string', example: '2024-11-22'),
                        new OA\Property(property: 'orderCount', type: 'integer', example: 15),
                    ],
                    type: 'object'
                )),
                new OA\Property(property: 'total', type: 'integer', example: 45),
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Invalid parameters provided.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: false),
                new OA\Property(property: 'message', type: 'string', example: 'Invalid groupBy parameter.'),
            ],
            type: 'object'
        )
    )]
    #[OA\Parameter(
        name: 'page',
        description: 'Текущая страница набора результатов.',
        in: 'query',
        schema: new OA\Schema(type: 'integer', minimum: 1),
        example: 1
    )]
    #[OA\Parameter(
        name: 'limit',
        description: 'Количество элементов на странице.',
        in: 'query',
        schema: new OA\Schema(type: 'integer', minimum: 1),
        example: 10
    )]
    #[OA\Parameter(
        name: 'groupBy',
        description: 'Критерии группировки заказов по дате (например, месяц, год или день).',
        in: 'query',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            enum: ['month', 'year', 'day'],
            example: 'month'
        )
    )]
    #[OA\Tag(name: 'Orders')]
    #[OA\Get(
        description: 'при GET запросе с пагинацией (страница, количество на странице) и группировкой. Отдать json с количеством заказов по месяцам, годам, дням. В ответе данные о станице, количестве на странице, количестве страниц итд и основная нагрузка - группирирующее значение и количество;',
        summary: 'Эндпоинт №2'
    )]
    public function getOrders(Request $request): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);
        $groupBy = $request->query->get('groupBy', 'month');
        if (empty($page)||empty($limit)||empty($groupBy)) {
            return new JsonResponse(['success'=>false, 'message' => 'Отсутствует обязательный параметр'], 400);
        }
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
    #[Route('/api/search', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the count of orders grouped by date.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'id', type: 'int', example: '2'),
                        new OA\Property(property: 'number', type: 'string', example: '#A123123'),
                        new OA\Property(property: 'url', type: 'string', example: 'http://localhost:8080/api/orders/2'),

                    ],
                    type: 'object'
                )),
                new OA\Property(property: 'total', type: 'integer', example: 45),
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Invalid parameters provided.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'success', type: 'boolean', example: false),
                new OA\Property(property: 'message', type: 'string', example: 'Invalid Request parameter.'),
            ],
            type: 'object'
        )
    )]
    #[OA\Parameter(
        name: 'term',
        description: 'Строка поиска',
        in: 'query',
        schema: new OA\Schema(type: 'string', minimum: 3),
        example: 'Ужгород'
    )]
    #[OA\Parameter(
        name: 'page',
        description: 'offset',
        in: 'query',
        schema: new OA\Schema(type: 'integer', minimum: 0),
        example: 0
    )]
    #[OA\Parameter(
        name: 'limit',
        description: 'Limit',
        in: 'query',
        schema: new OA\Schema(type: 'integer', minimum: 1),
        example: 10
    )]
    #[OA\Parameter(
        name: 'field_weights',
        description: 'Приоритетное поля для поиска',
        in: 'query',
        required: false,
        schema: new OA\Schema(
            type: 'string',
            enum: [
                "hash",
                "number",
                "email",
                "company_name",
                "name",
                "description",
                "client_name",
                "client_surname",
                "delivery_region",
                "delivery_city",
                "delivery_address",
                "biling_region",
                "biling_city",
                "address_address",
                "manager_name",
                "manager_email",
                "manager_phone"],
            example: 'name'
        )
    )]
    #[OA\Tag(name: 'Orders')]
    #[OA\Get(
        description: 'Добавить возможность поиска через Мантикору (*).',
        summary: 'Эндпоинт №4+'
    )]
    #[Route('/orders/search', name: 'orders_search', methods: ['GET'])]
    public function searchOrders(Request $request): JsonResponse
    {
        $term = $request->query->get('term');

        $page = (int)$request->query->get('page', 0);
        $limit = (int)$request->query->get('limit', 10);
        $field_weights = $request->query->get('field_weights', null);
        if (!$term) {
            return new JsonResponse(['error' => 'Параметр "term" обязателен.'], 400);
        }
        if(!empty($field_weights) && !in_array($field_weights, ["hash",
                "number",
                "email",
                "company_name",
                "name",
                "description",
                "client_name",
                "client_surname",
                "delivery_region",
                "delivery_city",
                "delivery_address",
                "biling_region",
                "biling_city",
                "address_address",
                "manager_name",
                "manager_email",
                "manager_phone"])){
            return new JsonResponse(['error' => 'Недопустимый параметр "field_weights".'], 400);
        }
        try {

            $params = [
                'body' => [
                    'index' => 'idx_orders',
                    'query' => [
                        'match_phrase' => [
                            '*' => $term,
                        ]
                    ],

                    'limit' => $limit,
                    'offset' => $page,
                    'sort' => [
                        ['_score' => 'desc'],
                    ],
                    "track_scores" => true,
                    // "profile"=>true
                ]
            ];
            if (!empty($field_weights)) {
                $params['body']['options'] = ['field_weights' => [
                    $field_weights => 10,
                ]];

            }


            $response = $this->manticoreClient->search($params);


            $results = $response['hits']['hits'] ?? [];

            $orders = array_map(function ($result) {
                return [
                    'id' => $result['_id'],
                    'number' => $result['_source']['number'] ?? null,
                    'url' => $this->urlGenerator->generate('_api_/orders/{id}{._format}_get', [
                        'id' => $result['_id'],
                    ], UrlGeneratorInterface::ABSOLUTE_URL)
                ];
            }, $results);
            $res = ['success' => true,
                'total' => $response['hits']['total'],
                'data' => $orders];

            return new JsonResponse($res);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DomCrawler\Crawler;
use OpenApi\Attributes as OA;
class PriceController
{
    #[Route('/api/price', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns Price from page tile.expert',
    )]
    #[OA\Parameter(
        name: 'factory',
        in: 'query',
        description: 'factory',
        example: 'cobsa',
        schema: new OA\Schema(type: 'string')
    ),
        OA\Parameter(
            name: 'collection',
            in: 'query',
            example: 'manual',
            description: 'collection',
            schema: new OA\Schema(type: 'string')
        ),
        OA\Parameter(
            name: 'article',
            in: 'query',
            example: 'manu7530bcbm-manualbaltic7-5x30',
            description: 'article',
            schema: new OA\Schema(type: 'string')
        )
    ]

    #[OA\Tag(name: 'Эндпоинт №1 ')]
    #[OA\Get(
        summary: 'Эндпоинт №1',
        description: 'при GET запросе с параметрами factory=cobsa&collection=manual&article=manu7530bcbm-manualbaltic7-5x30 выводить цену в евро со страницы'
    )]
    public function getPrice(Request $request): JsonResponse
    {
        $factory = $request->query->get('factory');
        $collection = $request->query->get('collection');
        $article = $request->query->get('article');
        if(!$factory || !$collection || !$article) {
            return new JsonResponse(['error' => 'Недопустимые параметры'], 400);
        }
        try {
            $url = "https://tile.expert/fr/tile/$factory/$collection/a/$article";
            $html = file_get_contents($url);



            $crawler = new Crawler($html);
            $scriptContent = $crawler->filter('script[type="application/json"][data-js-react-on-rails-store="appStore"]')->first();
            $idElement = $crawler->filter('.slide-shown')->first()->attr('data-element-id');

// Проверяем, найден ли элемент, и получаем содержимое
            if ($scriptContent->count() > 0) {
                $jsonData = $scriptContent->text(); // Получаем текст внутри <script>
                $parsedData = json_decode($jsonData, true); // Парсим JSON в PHP-массив

                // Вывод результата
                //    var_dump($parsedData);
            } else {
                return new JsonResponse(['success' => false, 'message' => 'Элемент не найден.'], 404);
            }
            return new JsonResponse([
                'price' => $parsedData['slider']['elements'][$idElement]['priceEuroFr'],
                'factory' => $parsedData['slider']['elements'][$idElement]['collection']['factory']['url'],
                'collection' => $parsedData['slider']['elements'][$idElement]['collection']['url'],
                'article' => $parsedData['slider']['elements'][$idElement]['url'],
            ]);
        }catch (\Exception $e) {
                return new JsonResponse(['success' => false, 'message' => $e->getMessage()], 404);
            }

    }
}

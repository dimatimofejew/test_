Техническое задание
Необходимо создать простое приложение и приложить ссылку на архив или Git-репозиторий (предпочтительнее).

Приложение должно собираться при помощи docker-compose (можно добавить сборку-запуск через make) и отвечать на http запросы.

Порты настраиваются в переменных окружения.

Приложение должно отвечать (эндпоинты описать в readme, идеальнее в сваггере):

Эндпоинт №1 - при GET запросе с параметрами factory=cobsa&collection=manual&article=manu7530bcbm-manualbaltic7-5x30 выводить цену в евро со страницы https://tile.expert/fr/tile/cobsa/manual/a/manu7530bcbm-manualbaltic7-5x30. Другие значения фабрики, коллекции, артикула должны отдать правильную цену. Ответ в виде json {"price": 38.99,"factory": "cobsa","collection": "manual","article": “manu7530bcbm-manualbaltic7-5x30”};
Эндпоинт №2 - при GET запросе с пагинацией (страница, количество на странице) и группировкой. Отдать json с количеством заказов по месяцам, годам, дням. В ответе данные о станице, количестве на странице, количестве страниц итд и основная нагрузка - группирирующее значение и количество;
Эндпоинт №3 (2? см REST API) - при SOAP запросе - сохранить (создать) данные.
Эндпоинт №4 - получение одного заказа.
Добавить возможность поиска через Мантикору (*).
Приложение должно быть покрыто основными тестами.

Необходимо описание что не так в таблице БД, как её улучшить. Приложить улучшенный дамп.

В гит-хистори должны быть коммиты с этапами выполнения задания.

дамп https://disk.yandex.ru/d/6EMZF6J6uM2ctw

Измените значения в файле .env.example
принять все значения для всех контейнеров

make env 

собрать и запустить 

make up  

остановить

make  down

пересобрать и перезапустить

make restart
спать осталась БД и SOAP test

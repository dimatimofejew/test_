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

тестирование
make test 


База Данных
new.sql
вынес некоторые данные повторяющиеся в отдельные таблицы //можно будет скармливать клиентам
не понятно какие данные клиента какие может быть дилера продавца (например мейл) - можно ли по нему идентифицировать клиента. (но проблема с ---@ya.ru но уникальным телефоном например не позволит да и он может быть пустой потому храним все главное продать)
хорошо было бы (или плохо) регионы, привязать к странам и города к регионам, но так как в заказе должны оставаться данные статичные, даже при смене названия региона или страны, то желательно бы было и страны записывать как сток а
высчитывание срока последней доставки (вообще-то должно быть высчитываемая) но к чему привязать не до конца понятно, адрес + служба доставки (не трогал так как возможно это мусор от клиентов)
packaging_count           double               not null comment 'Количество кратно которому можно добавлять товар в заказ',
по факту должно быть int //но заказчик сказал в упоковке 100, а продадим 0.2 --- и что тут поделать.

    delivery_time_min         date                 null comment 'Минимальный срок доставки',
    delivery_time_max         date                 null comment 'Максимальный срок доставки',
сроки храняться в часах (минутах) то есть int и в таблице продуктов
если же это дата предполагаемой доставки то да, и есть смысл хранить в заказе так для поиска крайних

amount                    double               not null comment 'количество артикулов в ед. измерения'
1.5 артикула?
все количество я бы перевел в int




Спасибо большое за задание (много е вспомнил, многому научился) приятно провел время.
Извените за спам у вас не отправляет копию анкеты
Expected response code 250 but got code \u0022\u0022, with message \u0022\u0022

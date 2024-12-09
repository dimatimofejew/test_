-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Дек 09 2024 г., 13:49
-- Версия сервера: 8.3.0
-- Версия PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `old`
--

-- --------------------------------------------------------

--
-- Структура таблицы `addresses`
--

CREATE TABLE `addresses` (
  `id` int NOT NULL,
  `client_id` int DEFAULT NULL,
  `country_id` int DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `building` varchar(200) DEFAULT NULL,
  `phone_code` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `apartment_office` varchar(30) DEFAULT NULL COMMENT 'Квартира/офис'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Хранит Данные адресов клиентов';

-- --------------------------------------------------------

--
-- Структура таблицы `carriers`
--

CREATE TABLE `carriers` (
  `id` int NOT NULL,
  `carrier_name` varchar(50) DEFAULT NULL COMMENT 'Название транспортной компании',
  `carrier_contact_data` varchar(255) DEFAULT NULL COMMENT 'Контактные данные транспортной компании'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Хранит информацию о транспортних компаниях';

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE `clients` (
  `id` int NOT NULL,
  `sex` smallint DEFAULT NULL COMMENT 'Пол клиента',
  `client_name` varchar(255) DEFAULT NULL COMMENT 'Имя клиента',
  `client_surname` varchar(255) DEFAULT NULL COMMENT 'Фамилия клиента',
  `email` varchar(100) DEFAULT NULL COMMENT 'контактный E-mail'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Хранит информацию о клиентах';

-- --------------------------------------------------------

--
-- Структура таблицы `countries`
--

CREATE TABLE `countries` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'Название страны'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Хранит информацию о странах';

-- --------------------------------------------------------

--
-- Структура таблицы `managers`
--

CREATE TABLE `managers` (
  `id` int NOT NULL,
  `manager_name` varchar(20) DEFAULT NULL COMMENT 'Имя менеджера сопровождающего заказ',
  `manager_email` varchar(30) DEFAULT NULL COMMENT 'Email менеджера сопровождающего заказ',
  `manager_phone` varchar(20) DEFAULT NULL COMMENT 'Телефон менеджера сопровождающего заказ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Хранит информацию о менеджерах';

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `hash` varchar(32) NOT NULL COMMENT 'hash заказа',
  `user_id` int DEFAULT NULL,
  `token` varchar(64) NOT NULL COMMENT 'уникальный хеш пользователя',
  `number` varchar(10) DEFAULT NULL COMMENT 'Номер заказа',
  `status` int NOT NULL DEFAULT '1' COMMENT 'Статус заказа',
  `email` varchar(100) DEFAULT NULL COMMENT 'контактный E-mail',
  `vat_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Частное лицо или плательщик НДС',
  `vat_number` varchar(100) DEFAULT NULL COMMENT 'НДС-номер',
  `tax_number` varchar(50) DEFAULT NULL COMMENT 'Индивидуальный налоговый номер налогоплательщика',
  `discount` smallint DEFAULT NULL COMMENT 'Процент скидки',
  `delivery` decimal(10,2) DEFAULT NULL COMMENT 'Стоимость доставки',
  `delivery_type` tinyint(1) DEFAULT '0' COMMENT 'Тип доставки: 0 - адрес клинта, 1 - адрес склада',
  `delivery_time_min` date DEFAULT NULL COMMENT 'Минимальный срок доставки',
  `delivery_time_max` date DEFAULT NULL COMMENT 'Максимальный срок доставки',
  `delivery_time_confirm_min` date DEFAULT NULL COMMENT 'Минимальный срок доставки подтверждённый производителем',
  `delivery_time_confirm_max` date DEFAULT NULL COMMENT 'Максимальный срок доставки подтверждённый производителем',
  `delivery_time_fast_pay_min` date DEFAULT NULL COMMENT 'Минимальный срок доставки',
  `delivery_time_fast_pay_max` date DEFAULT NULL COMMENT 'Максимальный срок доставки',
  `delivery_old_time_min` date DEFAULT NULL COMMENT 'Прошлый минимальный срок доставки',
  `delivery_old_time_max` date DEFAULT NULL COMMENT 'Прошлый максимальный срок доставки',
  `delivery_index` varchar(20) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL COMMENT 'Название компании',
  `pay_type` smallint NOT NULL COMMENT 'Выбранный тип оплаты',
  `pay_date_execution` datetime DEFAULT NULL COMMENT 'Дата до которой действует текущая цена заказа',
  `offset_date` datetime DEFAULT NULL COMMENT 'Дата сдвига предполагаемого расчета доставки',
  `offset_reason` smallint DEFAULT NULL COMMENT 'тип причина сдвига сроков 1 - каникулы на фабрике, 2 - фабрика уточняет сроки пр-ва, 3 - другое',
  `proposed_date` datetime DEFAULT NULL COMMENT 'Предполагаемая дата поставки',
  `ship_date` datetime DEFAULT NULL COMMENT 'Предполагаемая дата отгрузки',
  `tracking_number` varchar(50) DEFAULT NULL COMMENT 'Номер треккинга',
  `locale` varchar(5) NOT NULL COMMENT 'локаль из которой был оформлен заказ',
  `cur_rate` decimal(15,6) DEFAULT '1.000000' COMMENT 'курс на момент оплаты',
  `currency` varchar(3) NOT NULL DEFAULT 'EUR' COMMENT 'валюта при которой был оформлен заказ',
  `measure` varchar(3) NOT NULL DEFAULT 'm' COMMENT 'ед. изм. в которой был оформлен заказ',
  `name` varchar(200) NOT NULL COMMENT 'Название заказа',
  `description` varchar(1000) DEFAULT NULL COMMENT 'Дополнительная информация',
  `create_date` datetime NOT NULL COMMENT 'Дата создания',
  `update_date` datetime DEFAULT NULL COMMENT 'Дата изменения',
  `warehouse_data` longtext COMMENT 'Данные склада: адрес, название, часы работы',
  `step` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'если true то заказ не будет сброшен в следствии изменений',
  `address_equal` tinyint(1) DEFAULT '1' COMMENT 'Адреса плательщика и получателя совпадают (false - разные, true - одинаковые )',
  `bank_transfer_requested` tinyint(1) DEFAULT NULL COMMENT 'Запрашивался ли счет на банковский перевод',
  `accept_pay` tinyint(1) DEFAULT NULL COMMENT 'Если true то заказ отправлен в работу',
  `cancel_date` datetime DEFAULT NULL COMMENT 'Конечная дата согласования сроков поставки',
  `weight_gross` double DEFAULT NULL COMMENT 'Общий вес брутто заказа',
  `product_review` tinyint(1) DEFAULT NULL COMMENT 'Оставлен отзыв по коллекциям в заказе',
  `mirror` smallint DEFAULT NULL COMMENT 'Метка зеркала на котором создается заказ',
  `process` tinyint(1) DEFAULT NULL COMMENT 'метка массовой обработки',
  `fact_date` datetime DEFAULT NULL COMMENT 'Фактическая дата поставки',
  `entrance_review` smallint DEFAULT NULL COMMENT 'Фиксирует вход клиента на страницу отзыва и последующие клики',
  `payment_euro` tinyint(1) DEFAULT '0' COMMENT 'Если true, то оплату посчитать в евро',
  `spec_price` tinyint(1) DEFAULT NULL COMMENT 'установлена спец цена по заказу',
  `show_msg` tinyint(1) DEFAULT NULL COMMENT 'Показывать спец. сообщение',
  `delivery_price_euro` double DEFAULT NULL COMMENT 'Стоимость доставки в евро',
  `address_payer` int DEFAULT NULL,
  `sending_date` datetime DEFAULT NULL COMMENT 'Расчетная дата поставки',
  `delivery_calculate_type` tinyint(1) DEFAULT '0' COMMENT 'Тип расчета: 0 - ручной, 1 - автоматический',
  `full_payment_date` date DEFAULT NULL COMMENT 'Дата полной оплаты заказа',
  `bank_details` longtext COMMENT 'Реквизиты банка для возврата средств'
) ENGINE=InnoDB AVG_ROW_LENGTH=2209 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Хранит информацию о заказах';

-- --------------------------------------------------------

--
-- Структура таблицы `orders_article`
--

CREATE TABLE `orders_article` (
  `id` int NOT NULL,
  `orders_id` int DEFAULT NULL,
  `article_id` int DEFAULT NULL COMMENT 'ID коллекции',
  `amount` double NOT NULL COMMENT 'количество артикулов в ед. измерения',
  `price` double NOT NULL COMMENT 'Цена на момент оплаты заказа',
  `price_eur` double DEFAULT NULL COMMENT 'Цена в Евро по заказу',
  `currency` varchar(3) DEFAULT NULL COMMENT 'Валюта для которой установлена цена',
  `measure` varchar(2) DEFAULT NULL COMMENT 'Ед. изм. для которой установлена цена',
  `delivery_time_min` date DEFAULT NULL COMMENT 'Минимальный срок доставки',
  `delivery_time_max` date DEFAULT NULL COMMENT 'Максимальный срок доставки',
  `weight` double NOT NULL COMMENT 'вес упаковки',
  `multiple_pallet` smallint DEFAULT NULL COMMENT 'Кратность палете, 1 - кратно упаковке, 2 - кратно палете, 3 - не меньше палеты',
  `packaging_count` double NOT NULL COMMENT 'Количество кратно которому можно добавлять товар в заказ',
  `pallet` double NOT NULL COMMENT 'количество в палете на момент заказа',
  `packaging` double NOT NULL COMMENT 'количество в упаковке',
  `swimming_pool` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Плитка специально для бассейна'
) ENGINE=InnoDB AVG_ROW_LENGTH=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Хранит информацию об артикулах заказа';

-- --------------------------------------------------------

--
-- Структура таблицы `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int NOT NULL,
  `warehouse_data` longtext COMMENT 'Данные склада: адрес, название, часы работы'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Хранит Данные склада';

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_id` (`country_id`);

--
-- Индексы таблицы `carriers`
--
ALTER TABLE `carriers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2` (`user_id`),
  ADD KEY `IDX_3` (`create_date`),
  ADD KEY `IDX_4` (`create_date`,`status`),
  ADD KEY `IDX_5` (`hash`);

--
-- Индексы таблицы `orders_article`
--
ALTER TABLE `orders_article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_318C0B7C7294869C` (`article_id`),
  ADD KEY `IDX_318C0B7C7FC358ED` (`orders_id`);

--
-- Индексы таблицы `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `carriers`
--
ALTER TABLE `carriers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `managers`
--
ALTER TABLE `managers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `orders_article`
--
ALTER TABLE `orders_article`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders_article`
--
ALTER TABLE `orders_article`
  ADD CONSTRAINT `orders_article_ibfk_1` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

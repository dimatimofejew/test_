CREATE TABLE orders_article
(
    id                INT AUTO_INCREMENT NOT NULL,
    orders_id         INT              DEFAULT NULL,
    article_id        INT              DEFAULT NULL COMMENT 'ID коллекции',
    amount            INT           NOT NULL COMMENT 'количество артикулов в ед. измерения',
    price             DOUBLE PRECISION           NOT NULL COMMENT 'Цена на момент оплаты заказа',
    price_eur         DOUBLE PRECISION DEFAULT NULL COMMENT 'Цена в Евро по заказу',
    currency          VARCHAR(3)       DEFAULT NULL COMMENT 'Валюта для которой установлена цена',
    measure           VARCHAR(2)       DEFAULT NULL COMMENT 'Ед. изм. для которой установлена цена',
    delivery_time_min DATE             DEFAULT NULL COMMENT 'Минимальный срок доставки',
    delivery_time_max DATE             DEFAULT NULL COMMENT 'Максимальный срок доставки',
    weight            DOUBLE PRECISION           NOT NULL COMMENT 'вес упаковки',
    multiple_pallet   SMALLINT         DEFAULT NULL COMMENT 'Кратность палете, 1 - кратно упаковке, 2 - кратно палете, 3 - не меньше палеты',
    packaging_count   INT           NOT NULL COMMENT 'Количество кратно которому можно добавлять товар в заказ',
    pallet            INT           NOT NULL COMMENT 'количество в палете на момент заказа',
    packaging         INT           NOT NULL COMMENT 'количество в упаковке',
    swimming_pool     INT              DEFAULT 0 NOT NULL COMMENT 'Плитка специально для бассейна',
    INDEX             IDX_318C0B7C7294869C (article_id),
    INDEX             IDX_318C0B7C7FC358ED (orders_id),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE warehouses
(
    id             INT AUTO_INCREMENT NOT NULL,
    warehouse_data LONGTEXT DEFAULT NULL COMMENT 'Данные склада: адрес, название, часы работы',
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE carriers
(
    id                   INT AUTO_INCREMENT NOT NULL,
    carrier_name         VARCHAR(50)  DEFAULT NULL COMMENT 'Название транспортной компании',
    carrier_contact_data VARCHAR(255) DEFAULT NULL COMMENT 'Контактные данные транспортной компании',
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE addresses
(
    id               INT AUTO_INCREMENT NOT NULL,
    client_id        INT          DEFAULT NULL,
    country_id       INT          DEFAULT NULL,
    region           VARCHAR(50)  DEFAULT NULL,
    city             VARCHAR(200) DEFAULT NULL,
    address          VARCHAR(300) DEFAULT NULL,
    building         VARCHAR(200) DEFAULT NULL,
    phone_code       VARCHAR(20)  DEFAULT NULL,
    phone            VARCHAR(20)  DEFAULT NULL,
    apartment_office VARCHAR(30)  DEFAULT NULL COMMENT 'Квартира/офис',
    INDEX            country_id (country_id),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE managers
(
    id            INT AUTO_INCREMENT NOT NULL,
    manager_name  VARCHAR(20) DEFAULT NULL COMMENT 'Имя менеджера сопровождающего заказ',
    manager_email VARCHAR(30) DEFAULT NULL COMMENT 'Email менеджера сопровождающего заказ',
    manager_phone VARCHAR(20) DEFAULT NULL COMMENT 'Телефон менеджера сопровождающего заказ',
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE clients
(
    id             INT AUTO_INCREMENT NOT NULL,
    sex            SMALLINT     DEFAULT NULL COMMENT 'Пол клиента',
    client_name    VARCHAR(255) DEFAULT NULL COMMENT 'Имя клиента',
    client_surname VARCHAR(255) DEFAULT NULL COMMENT 'Фамилия клиента',
    email          VARCHAR(100) DEFAULT NULL COMMENT 'контактный E-mail',
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE countries
(
    id   INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(100) NOT NULL COMMENT 'Название страны',
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE orders
(
    id                         INT AUTO_INCREMENT NOT NULL,
    manager_id                 INT              DEFAULT NULL,
    delivery_addr_id           INT              DEFAULT NULL,
    biling_addr_id             INT              DEFAULT NULL,
    client_id                  INT              DEFAULT NULL,
    carrier_id                 INT              DEFAULT NULL,
    warehouse_id               INT              DEFAULT NULL,
    hash                       VARCHAR(32)                    NOT NULL COMMENT 'hash заказа',
    user_id                    INT              DEFAULT NULL,
    token                      VARCHAR(64)                    NOT NULL COMMENT 'уникальный хеш пользователя',
    number                     VARCHAR(10)      DEFAULT NULL COMMENT 'Номер заказа',
    status                     INT              DEFAULT 1     NOT NULL COMMENT 'Статус заказа',
    email                      VARCHAR(100)     DEFAULT NULL COMMENT 'контактный E-mail',
    vat_type                   INT              DEFAULT 0     NOT NULL COMMENT 'Частное лицо или плательщик НДС',
    vat_number                 VARCHAR(100)     DEFAULT NULL COMMENT 'НДС-номер',
    tax_number                 VARCHAR(50)      DEFAULT NULL COMMENT 'Индивидуальный налоговый номер налогоплательщика',
    discount                   SMALLINT         DEFAULT NULL COMMENT 'Процент скидки',
    delivery                   DOUBLE PRECISION DEFAULT NULL COMMENT 'Стоимость доставки',
    delivery_type              INT              DEFAULT 0 COMMENT 'Тип доставки: 0 - адрес клинта, 1 - адрес склада',
    delivery_time_min          DATE             DEFAULT NULL COMMENT 'Минимальный срок доставки',
    delivery_time_max          DATE             DEFAULT NULL COMMENT 'Максимальный срок доставки',
    delivery_time_confirm_min  DATE             DEFAULT NULL COMMENT 'Минимальный срок доставки подтверждённый производителем',
    delivery_time_confirm_max  DATE             DEFAULT NULL COMMENT 'Максимальный срок доставки подтверждённый производителем',
    delivery_time_fast_pay_min DATE             DEFAULT NULL COMMENT 'Минимальный срок доставки',
    delivery_time_fast_pay_max DATE             DEFAULT NULL COMMENT 'Максимальный срок доставки',
    delivery_old_time_min      DATE             DEFAULT NULL COMMENT 'Прошлый минимальный срок доставки',
    delivery_old_time_max      DATE             DEFAULT NULL COMMENT 'Прошлый максимальный срок доставки',
    delivery_index             VARCHAR(20)      DEFAULT NULL,
    company_name               VARCHAR(255)     DEFAULT NULL COMMENT 'Название компании',
    pay_type                   SMALLINT                       NOT NULL COMMENT 'Выбранный тип оплаты',
    pay_date_execution         DATETIME         DEFAULT NULL COMMENT 'Дата до которой действует текущая цена заказа',
    offset_date                DATETIME         DEFAULT NULL COMMENT 'Дата сдвига предполагаемого расчета доставки',
    offset_reason              SMALLINT         DEFAULT NULL COMMENT 'тип причина сдвига сроков 1 - каникулы на фабрике, 2 - фабрика уточняет сроки пр-ва, 3 - другое',
    proposed_date              DATETIME         DEFAULT NULL COMMENT 'Предполагаемая дата поставки',
    ship_date                  DATETIME         DEFAULT NULL COMMENT 'Предполагаемая дата отгрузки',
    tracking_number            VARCHAR(50)      DEFAULT NULL COMMENT 'Номер треккинга',
    locale                     VARCHAR(5)                     NOT NULL COMMENT 'локаль из которой был оформлен заказ',
    cur_rate                   DOUBLE PRECISION DEFAULT '1' COMMENT 'курс на момент оплаты',
    currency                   VARCHAR(3)       DEFAULT 'EUR' NOT NULL COMMENT 'валюта при которой был оформлен заказ',
    measure                    VARCHAR(3)       DEFAULT 'm'   NOT NULL COMMENT 'ед. изм. в которой был оформлен заказ',
    name                       VARCHAR(200)                   NOT NULL COMMENT 'Название заказа',
    description                VARCHAR(1000)    DEFAULT NULL COMMENT 'Дополнительная информация',
    create_date                DATETIME                       NOT NULL COMMENT 'Дата создания',
    update_date                DATETIME         DEFAULT NULL COMMENT 'Дата изменения',
    step                       INT              DEFAULT 1     NOT NULL COMMENT 'если true то заказ не будет сброшен в следствии изменений',
    address_equal              INT              DEFAULT 1 COMMENT 'Адреса плательщика и получателя совпадают (false - разные, true - одинаковые )',
    bank_transfer_requested    INT              DEFAULT NULL COMMENT 'Запрашивался ли счет на банковский перевод',
    accept_pay                 INT              DEFAULT NULL COMMENT 'Если true то заказ отправлен в работу',
    cancel_date                DATETIME         DEFAULT NULL COMMENT 'Конечная дата согласования сроков поставки',
    weight_gross               DOUBLE PRECISION DEFAULT NULL COMMENT 'Общий вес брутто заказа',
    product_review             INT              DEFAULT NULL COMMENT 'Оставлен отзыв по коллекциям в заказе',
    mirror                     SMALLINT         DEFAULT NULL COMMENT 'Метка зеркала на котором создается заказ',
    process                    INT              DEFAULT NULL COMMENT 'метка массовой обработки',
    fact_date                  DATETIME         DEFAULT NULL COMMENT 'Фактическая дата поставки',
    entrance_review            SMALLINT         DEFAULT NULL COMMENT 'Фиксирует вход клиента на страницу отзыва и последующие клики',
    payment_euro               INT              DEFAULT 0 COMMENT 'Если true, то оплату посчитать в евро',
    spec_price                 INT              DEFAULT NULL COMMENT 'установлена спец цена по заказу',
    show_msg                   INT              DEFAULT NULL COMMENT 'Показывать спец. сообщение',
    delivery_price_euro        DOUBLE PRECISION DEFAULT NULL COMMENT 'Стоимость доставки в евро',
    address_payer              INT              DEFAULT NULL,
    sending_date               DATETIME         DEFAULT NULL COMMENT 'Расчетная дата поставки',
    delivery_calculate_type    INT              DEFAULT 0 COMMENT 'Тип расчета: 0 - ручной, 1 - автоматический',
    full_payment_date          DATE             DEFAULT NULL COMMENT 'Дата полной оплаты заказа',
    bank_details               LONGTEXT         DEFAULT NULL COMMENT 'Реквизиты банка для возврата средств',
    INDEX                      IDX_E52FFDEE783E3463 (manager_id),
    INDEX                      IDX_E52FFDEE1D52CF05 (delivery_addr_id),
    INDEX                      IDX_E52FFDEEC2A5FDA0 (biling_addr_id),
    INDEX                      IDX_E52FFDEE19EB6921 (client_id),
    INDEX                      IDX_E52FFDEE21DFC797 (carrier_id),
    INDEX                      IDX_E52FFDEE5080ECDE (warehouse_id),
    INDEX                      IDX_2 (user_id),
    INDEX                      IDX_3 (create_date),
    INDEX                      IDX_4 (create_date, status),
    INDEX                      IDX_5 (hash),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE messenger_messages
(
    id           BIGINT AUTO_INCREMENT NOT NULL,
    body         LONGTEXT     NOT NULL,
    headers      LONGTEXT     NOT NULL,
    queue_name   VARCHAR(190) NOT NULL,
    created_at   DATETIME     NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    available_at DATETIME     NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    INDEX        IDX_75EA56E0FB7336F0 (queue_name),
    INDEX        IDX_75EA56E0E3BD61CE (available_at),
    INDEX        IDX_75EA56E016BA31DB (delivered_at),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE orders
    ADD CONSTRAINT FK_E52FFDEE783E3463 FOREIGN KEY (manager_id) REFERENCES managers (id);
ALTER TABLE orders
    ADD CONSTRAINT FK_E52FFDEE1D52CF05 FOREIGN KEY (delivery_addr_id) REFERENCES addresses (id);
ALTER TABLE orders
    ADD CONSTRAINT FK_E52FFDEEC2A5FDA0 FOREIGN KEY (biling_addr_id) REFERENCES addresses (id);
ALTER TABLE orders
    ADD CONSTRAINT FK_E52FFDEE19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id);
ALTER TABLE orders
    ADD CONSTRAINT FK_E52FFDEE21DFC797 FOREIGN KEY (carrier_id) REFERENCES carriers (id);
ALTER TABLE orders
    ADD CONSTRAINT FK_E52FFDEE5080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouses (id);

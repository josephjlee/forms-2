CREATE TABLE `{form}`
(
    `id`            INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `title`         VARCHAR(256)        NOT NULL DEFAULT '',
    `slug`          VARCHAR(255)        NOT NULL DEFAULT '',
    `description`   TEXT,
    `status`        TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
    `time_create`   INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `time_start`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `time_end`      INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `count`         INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `main_image`    INT(10) UNSIGNED    NULL     DEFAULT NULL,
    `register_need` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
    `review_need`   TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    `show_answer`   TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    `multi_steps`   TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    `review_action` VARCHAR(32)         NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`),
    KEY `status` (`status`),
    KEY `time_create` (`time_create`),
    KEY `time_start` (`time_start`),
    KEY `time_end` (`time_end`),
    KEY `select` (`status`, `time_start`, `time_end`)
);

CREATE TABLE `{element}`
(
    `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `title`       VARCHAR(256)        NOT NULL DEFAULT '',
    `required`    TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    `type`        ENUM (
        'text', 'number', 'email', 'url', 'tel',
        'textarea', 'checkbox', 'radio', 'select'
        )                             NOT NULL DEFAULT 'text',
    `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
    `order`       INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `value`       TEXT,
    `description` TEXT,
    `answer`      MEDIUMTEXT,
    `is_name`     TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    `is_email`    TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    `is_mobile`   TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
);

CREATE TABLE `{link}`
(
    `id`      INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `form`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `element` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `status`  TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`),
    KEY `form` (`form`),
    KEY `element` (`element`),
    KEY `form_element` (`form`, `element`)
);

CREATE TABLE `{record}`
(
    `id`            INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `uid`           INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `form`          INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `time_create`   INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `ip`            CHAR(15)            NOT NULL DEFAULT '',
    `status`        TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
    `review_status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0', # 0:Pending, 1:Accepted, 2:Rejected
    `review_result` TEXT,
    PRIMARY KEY (`id`),
    KEY `uid` (`uid`),
    KEY `form` (`form`),
    KEY `uid_form` (`uid`, `form`)
);

CREATE TABLE `{data}`
(
    `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `record`      INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `uid`         INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `form`        INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `element`     INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `time_create` INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `value`       TEXT,
    PRIMARY KEY (`id`),
    KEY `form` (`form`)
);

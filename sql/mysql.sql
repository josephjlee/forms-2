CREATE TABLE `{form}` (
  `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(256)        NOT NULL DEFAULT '',
  `slug`        VARCHAR(255)        NOT NULL DEFAULT '',
  `description` TEXT,
  `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `time_create` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `time_start`  INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `time_end`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `count`       INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `main_image`  INT(10) UNSIGNED    NULL     DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `time_create` (`time_create`),
  KEY `time_start` (`time_start`),
  KEY `time_end` (`time_end`),
  KEY `select` (`status`, `time_start`, `time_end`)
);

CREATE TABLE `{element}` (
  `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(256)        NOT NULL DEFAULT '',
  `description` TEXT,
  `required`    TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `type`        ENUM ('text', 'number', 'email',
                      'phone', 'textarea', 'checkbox',
                      'radio', 'select', 'star',
                      'percent')    NOT NULL DEFAULT 'text',
  `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `order`       INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `value`       TEXT,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{link}` (
  `id`      INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `form`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `element` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `status`  TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `form` (`form`),
  KEY `element` (`element`),
  KEY `form_element` (`form`, `element`)
);

CREATE TABLE `{record}` (
  `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `uid`         INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `form`        INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `extra_key`   INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `time_create` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `ip`          CHAR(15)            NOT NULL DEFAULT '',
  `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `form` (`form`),
  KEY `uid_form` (`uid`, `form`),
  KEY `uid_form_key` (`uid`, `form`, `extra_key`)
);

CREATE TABLE `{data}` (
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

CREATE TABLE `{extra}` (
  `id`           INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `form`         INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `extra_key`    INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `extra_module` VARCHAR(16)      NOT NULL DEFAULT 'shop',
  PRIMARY KEY (`id`),
  KEY `form` (`form`),
  KEY `extra_key` (`extra_key`)
);
CREATE TABLE `{form}` (
  `id`               INT(10) UNSIGNED              NOT NULL AUTO_INCREMENT,
  `title`            VARCHAR(256)                  NOT NULL DEFAULT '',
  `slug`             VARCHAR(255)                  NOT NULL DEFAULT '',
  `text_description` TEXT,
  `status`           TINYINT(1) UNSIGNED           NOT NULL DEFAULT '1',
  `time_create`      INT(10) UNSIGNED              NOT NULL DEFAULT '0',
  `time_start`       INT(10) UNSIGNED              NOT NULL DEFAULT '0',
  `time_end`         INT(10) UNSIGNED              NOT NULL DEFAULT '0',
  `type`             ENUM ('general', 'dedicated') NOT NULL DEFAULT 'general',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `status` (`status`),
  KEY `time_create` (`time_create`),
  KEY `time_start` (`time_start`),
  KEY `time_end` (`time_end`),
  KEY `type` (`type`),
  KEY `select` (`status`, `type`, `time_start`, `time_end`)
);

CREATE TABLE `{element}` (
  `id`     INT(10) UNSIGNED                                                           NOT NULL AUTO_INCREMENT,
  `title`  VARCHAR(256)                                                               NOT NULL DEFAULT '',
  `type`   ENUM ('text', 'email', 'phone', 'textarea', 'checkbox', 'radio', 'select') NOT NULL DEFAULT '',
  `status` TINYINT(1) UNSIGNED                                                        NOT NULL DEFAULT '1',
  `order`  INT(10) UNSIGNED                                                           NOT NULL DEFAULT '0',
  `value`  TEXT,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{link}` (
  `id`      INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `from`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `element` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `status`  TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `from` (`from`),
  KEY `element` (`element`),
  KEY `from_element` (`from`, `element`)
);

CREATE TABLE `{record}` (
  `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `uid`         INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `from`        INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `time_create` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `ip`          CHAR(15)            NOT NULL DEFAULT '',
  `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `from` (`from`),
  KEY `uid_from` (`uid`, `from`)
);

CREATE TABLE `{data}` (
  `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `record`      INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `uid`         INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `from`        INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `element`     INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `time_create` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `value`       TEXT,
  PRIMARY KEY (`id`),
  KEY `from` (`from`)
);
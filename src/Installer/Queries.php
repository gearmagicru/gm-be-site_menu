<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации Карты SQL-запросов.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'drop'   => ['{{menu}}', '{{menu_items}}'],
    'create' => [
        '{{menu}}' => function () {
            return "CREATE TABLE {{menu}} (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(120) DEFAULT NULL,
                `description` varchar(255) DEFAULT NULL,
                `visible` tinyint(1) unsigned DEFAULT '1',
                PRIMARY KEY (`id`)
            ) ENGINE={engine} 
            DEFAULT CHARSET={charset} COLLATE {collate}";
        },
        
        '{{menu_items}}' => function () {
            return "CREATE TABLE {{menu_items}} (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `parent_id` int(11) unsigned DEFAULT NULL,
                `menu_id` int(11) unsigned DEFAULT NULL,
                `link_id` int(11) unsigned DEFAULT NULL,
                `language_id` int(11) unsigned DEFAULT NULL,
                `type` varchar(50) DEFAULT NULL,
                `count` int(11) unsigned DEFAULT NULL,
                `index` int(11) unsigned DEFAULT '1',
                `name` varchar(255) DEFAULT NULL,
                `description` text,
                `title` varchar(255) DEFAULT NULL,
                `css` varchar(50) DEFAULT NULL,
                `rel` varchar(50) DEFAULT NULL,
                `url` varchar(255) DEFAULT NULL,
                `external_url` tinyint(1) unsigned DEFAULT '0',
                `image_url` varchar(255) DEFAULT NULL,
                `visible` tinyint(1) unsigned DEFAULT '1',
                PRIMARY KEY (`id`)
            ) ENGINE={engine} 
            DEFAULT CHARSET={charset} COLLATE {collate}";
        }
    ],

    'run' => [
        'install'   => ['drop', 'create'],
        'uninstall' => ['drop']
    ]
];
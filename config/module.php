<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации модуля.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'translator' => [
        'locale'   => 'auto',
        'patterns' => [
            'text' => [
                'basePath' => __DIR__ . '/../lang',
                'pattern'   => 'text-%s.php'
            ]
        ],
        'autoload' => ['text'],
        'external' => [BACKEND]
    ],

    'accessRules' => [
        // для авторизованных пользователей Панели управления
        [ // разрешение "Полный доступ" (any: view, read, add, edit, delete, clear)
            'allow',
            'permission'  => 'any',
            'controllers' => [
                'Grid'      => ['data', 'view', 'update', 'delete', 'clear', 'show', 'hide'],
                'ItemsGrid' => ['data', 'view', 'update', 'delete', 'clear', 'moveup', 'movedown', 'move', 'filter'],
                'Form'      => ['data', 'view', 'add', 'update', 'delete'],
                'ItemForm'  => ['data', 'view', 'add', 'update', 'delete'],
                'Search'    => ['data', 'view'],
                'Trigger'   => ['combo']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Просмотр" (view)
            'allow',
            'permission'  => 'view',
            'controllers' => [
                'Grid'      => ['data', 'view'],
                'ItemsGrid' => ['data', 'view', 'filter'],
                'Form'      => ['data', 'view'],
                'ItemForm'  => ['data', 'view'],
                'Search'    => ['data', 'view'],
                'Trigger'   => ['combo']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Чтение" (read)
            'allow',
            'permission'  => 'read',
            'controllers' => [
                'Grid'      => ['data'],
                'ItemsGrid' => ['data'],
                'Form'      => ['data'],
                'ItemForm'  => ['data'],
                'Search'    => ['data'],
                'Trigger'   => ['combo']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Добавление" (add)
            'allow',
            'permission'  => 'add',
            'controllers' => [
                'Form'     => ['add'],
                'ItemForm' => ['add'],
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Изменение" (edit)
            'allow',
            'permission'  => 'edit',
            'controllers' => [
                'Grid'       => ['update'],
                'ItemsGrid'  => ['update'],
                'Form'       => ['update'],
                'ItemForm'   => ['update']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Удаление" (delete)
            'allow',
            'permission'  => 'delete',
            'controllers' => [
                'Grid'      => ['delete'],
                'ItemsGrid' => ['delete'],
                'Form'      => ['delete'],
                'ItemForm'  => ['delete']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Очистка" (clear)
            'allow',
            'permission'  => 'clear',
            'controllers' => [
                'Grid'      => ['clear'],
                'ItemsGrid' => ['clear']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Информация о модуле" (info)
            'allow',
            'permission'  => 'info',
            'controllers' => ['Info'],
            'users'       => ['@backend']
        ],
        [ // для всех остальных, доступа нет
            'deny'
        ]
    ],

    'viewManager' => [
        'id'          => 'gm-site-menu-{name}',
        'useTheme'    => true,
        'useLocalize' => true,
        'viewMap'     => [
            // информации о модуле
            'info' => [
                'viewFile'      => '//backend/module-info.phtml', 
                'forceLocalize' => true
            ],
            'form' => '/form.json'
        ]
    ]
];

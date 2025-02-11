<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет английской (британской) локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Site menu',
    '{description}' => 'Site menu',
    '{permissions}' => [
        'any'       => ['Full access', 'Viewing and making changes to the menu'],
        'view'      => ['View', 'View menu'],
        'read'      => ['Reading', 'Reading contracts'],
        'add'       => ['Adding', 'Adding menu items'],
        'edit'      => ['Editing', 'Editing menu items'],
        'delete'    => ['Deleting', 'Deleting menu items'],
        'clear'     => ['Clear', 'Deleting all menu items'],
        'interface' => ['Interface', 'Accessing the Main menu interface'],
    ],

    // Form
    '{form.title}' => 'Create menu',
    '{form.titleTpl}' => 'Edit menu "{name}"',
    // Form: поля / Grid: столбцы
    'Name' => 'Name',
    'The name is displayed only for you' => 'The name is displayed only for you',
    'Description' => 'Description',
    'Description displayed only for you' => 'Description displayed only for you',
    'Visible' => 'Visible',

    // Grid: панель инструментов
    'Adding a new menu' => 'Adding a new menu',
    'Deleting selected menus' => 'Deleting selected menus',
    'Delete all menus' => 'Delete all menus',
    // Grid: контекстное меню записи
    'Edit menu' => 'Edit menu',
    // Grid: столбцы
    'Menu identifier' => 'Menu identifier',
    'Language' => 'Language',
    'Site menu items' => 'Site menu items',
    'Go to adding / editing menu items' => 'Go to adding / editing menu items',
    'Menu visibility' => 'Menu visibility',
    'Visible on site' => 'Visible on site',
    'yes' => 'yes',
    'no' => 'no',
    // Grid: сообщения / заголовки
    'Deleting a menu' => 'Deleting a menu',
    'Show' => 'Show',
    'Hide' => 'Hide',
    // Grid: сообщения / текст
    'Menu "{0}" - hide' => 'Menu "{0}" - hide.',
    'Menu "{0}" - show' => 'Menu "{0}" - show.',

    // ItemsGrid
    'Menu "{0}"' => 'Menu "{0}"',
    // ItemsGrid: панель инструментов
    'Adding a new item' => 'Adding a new item',
    'Deleting selected items' => 'Deleting selected items',
    'Delete all menu items' => 'Delete all menu items',
    // ItemsGrid: столбцы
    'Number of menu subitems' => 'Number of menu subitems',
    'Open the site page that the menu item refers to' => 'Open the site page that the menu item refers to',
    'Sequence number in the list' => 'Sequence number in the list',
    // ItemsGrid: навигация
    'Index number' => 'Index number',
    'Menu ID' => 'Menu ID',
    'Menu item ID' => 'Menu item ID',
    'Open the site page' => 'Open the site page',
    'yes' => 'yes',
    'no' => 'no',
    // ItemsGrid: контекстное меню записи
    'Add subitem' => 'Add subitem',
    'Edit item' => 'Edit item',
    'Move one level up' => 'Move one level up',
    'Move one level down' => 'Move one level down',
    // ItemsGrid: ошибки
    'Unable to determine menu' => 'Unable to determine menu.',
    'Menu item move error' => 'Menu item move error.',
    // ItemsGrid: сообщения / текст
    'Menu item "{0}" - hide' => 'Menu item "{0}" - hide.',
    'Menu item "{0}" - show' => 'Menu item "{0}" - show.',

    // ItemForm
    '{item.title}' => 'Adding a menu item',
    '{subitem.title}' => 'Adding a menu item to "{0}"',
    '{item.titleTpl}' => 'Edit a menu item "{name}"',
    // ItemForm: поля
    'Title' => 'Title',
    'The title attribute is the alternative title of the menu item that appears when you hover over it' 
        => 'The title attribute is the alternative title of the menu item that appears when you hover over it',
    'Class CSS'  => 'Class CSS',
    'Relationship to link' => 'Relationship to link',
    'Allows you to set an individual CSS class for a menu item. It is used to give a single item an individual style.' 
        => 'Allows you to set an individual CSS class for a menu item. It is used to give a single item an individual style.',
    'Type' => 'Type',
    'Menu item type' => 'Menu item type',
    'Arbitrary link' => 'Arbitrary link',
    'Article' => 'Article',
    'Article category' => 'Article category',
    'File on the server' => 'File on the server',
    'URL address' => 'URL address',
    'File URL' => 'File UR',
    'Icon URL' => 'Icon URL',
    'Anchor' => 'Anchor',
    'A bookmark with a unique name at a specific place on a web page, designed to create a transition to it via a link' 
        => 'A bookmark with a unique name at a specific place on a web page, designed to create a transition to it via a link',
    'Icon' => 'Icon',
    'Open in new window' => 'Open in new window',
    'Specifies a value for the rel attribute of menu items. Most often, nofollow or noindex is used for search engine optimization purposes.' 
        => 'Specifies a value for the rel attribute of menu items. Most often, nofollow or noindex is used for search engine optimization purposes.',
    // ItemForm: поля / rel 
    '[None]' => '[None]',
    'Link to site archive' => 'Link to site archive',
    'Link to the page about the author on the same domain' => 'Link to the page about the author on the same domain',
    'Permalink to a section or post' => 'Permalink to a section or post',
    'Link to first page' => 'Link to first page',
    'Link to help document' => 'Link to help document',
    'Link to content' => 'Link to content',
    'Link to last page' => 'Link to last page',
    'Link to license agreement or copyright page' => 'Link to license agreement or copyright page',
    'Link to the author\'s page on another domain' => 'Link to the author\'s page on another domain',
    'Link to next page or section' => 'Link to next page or section',
    'Do not send by reference TIC and PR' => 'Do not send by reference TIC and PR',
    'Do not pass HTTP headers by reference' => 'Do not pass HTTP headers by reference',
    'Specifies that the specified resource should be pre-cache' => 'Specifies that the specified resource should be pre-cache',
    'Link to the previous page or section' => 'Link to the previous page or section',
    'Search link' => 'Search link',
    'Add link to browser favorites' => 'Add link to browser favorites',
    'Indicates that the label (tag) is related to the current document' => 'Indicates that the label (tag) is related to the current document',
    'Link to parent page' => 'Link to parent page'
];

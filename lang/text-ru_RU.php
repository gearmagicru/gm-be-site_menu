<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет русской локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Меню сайта',
    '{description}' => 'Меню сайта',
    '{permissions}' => [
        'any'       => ['Полный доступ', 'Просмотр и внесение изменений в меню'],
        'view'      => ['Просмотр', 'Просмотр меню'],
        'read'      => ['Чтение', 'Чтение меню'],
        'add'       => ['Добавление', 'Добавление пунктов меню'],
        'edit'      => ['Изменение', 'Изменение пунктов меню'],
        'delete'    => ['Удаление', 'Удаление пунктов меню'],
        'clear'     => ['Очистка', 'Удаление всех пунктов меню'],
        'interface' => ['Интерфейс', 'Доступ к интерфейсу Главного меню'],
    ],

    // Form
    '{form.title}' => 'Создание меню',
    '{form.titleTpl}' => 'Изменение меню "{name}"',
    // Form: поля / Grid: столбцы
    'Name' => 'Название',
    'The name is displayed only for you' => 'Название отображается только для вас',
    'Description' => 'Описание',
    'Description displayed only for you' => 'Описание отображается только для вас',
    'Visible' => 'Видимый',

    // Grid: панель инструментов
    'Adding a new menu' => 'Добавление нового меню',
    'Deleting selected menus' => 'Удаление выделенных меню',
    'Delete all menus' => 'Удалить все меню',
    // Grid: контекстное меню записи
    'Edit menu' => 'Редактировать',
    // Grid: столбцы
    'Menu identifier' => 'Идентификатор меню',
    'Language' => 'Язык',
    'Site menu items' => 'Пункты меню сайта',
    'Go to adding / editing menu items' => 'Перейти к добавлению / редактированию пунктов меню',
    'Menu visibility' => 'Видимость меню',
    'Visible on site' => 'Отображается на сайте',
    'yes' => 'да',
    'no' => 'нет',
    // Grid: сообщения / заголовки
    'Deleting a menu' => 'Удаление меню',
    'Show' => 'Отобразить',
    'Hide' => 'Скрыть',
    // Grid: сообщения / текст
    'Menu "{0}" - hide' => 'Меню "{0}" - скрыто.',
    'Menu "{0}" - show' => 'Меню "{0}" - отображается.',

    // ItemsGrid
    'Menu "{0}"' => 'Меню "{0}"',
    // ItemsGrid: панель инструментов
    'Adding a new item' => 'Добавление нового пункта',
    'Deleting selected items' => 'Удаление выделенных пунктов',
    'Delete all menu items' => 'Удалить все пункты меню',
    // ItemsGrid: столбцы
    'Number of menu subitems' => 'Количество подпунктов',
    'Open the site page that the menu item refers to' => 'Открыть страницу сайта на которую ссылается пункт меню',
    'Sequence number in the list' => 'Порядковый номер в списке',
    // ItemsGrid: навигация
    'Index number' => 'Порядковый номер',
    'Menu ID' => 'Идентификатор меню',
    'Menu item ID' => 'Идентификатор пункта',
    'Open the site page' => 'Открыть страницу сайта',
    'yes' => 'да',
    'no' => 'нет',
    // ItemsGrid: контекстное меню записи
    'Add subitem' => 'Добавить подпункт меню',
    'Edit item' => 'Редактировать пункт меню',
    'Move one level up' => 'Переместить на уровень вверх',
    'Move one level down' => 'Переместить на уровень вниз',
    // ItemsGrid: ошибки
    'Unable to determine menu' => 'Невозможно определить меню.',
    'Menu item move error' => 'Ошибка перемещения пункта меню.',
    // ItemsGrid: сообщения / текст
    'Menu item "{0}" - hide' => 'Пункт меню "{0}" - скрыт.',
    'Menu item "{0}" - show' => 'Пункт меню "{0}" - отображается.',

    // ItemForm
    '{item.title}' => 'Добавление пункта меню',
    '{subitem.title}' => 'Добавление подункта меню в "{0}"',
    '{item.titleTpl}' => 'Изменение пункта меню "{name}"',
    // ItemForm: поля
    'Title' => 'Заголовок',
    'The title attribute is the alternative title of the menu item that appears when you hover over it' 
        => 'Это атрибут (title) альтернативного заголовка пункта меню, который появляется при наведении курсора на него',
    'Class CSS'  => 'Класс CSS',
    'Relationship to link' => 'Отношение к ссылке',
    'Allows you to set an individual CSS class for a menu item. It is used to give a single item an individual style.' 
        => 'Позволяет задать индивидуальный class CSS для пункта в меню. Применяется для придания отдельно взятому пункту индивидуального стиля.',
    'Type' => 'Тип',
    'Menu item type' => 'Тип пункта меню',
    'Arbitrary link' => 'Произвольная ссылка',
    'Article' => 'Статья',
    'Article category' => 'Категория статьи',
    'File on the server' => 'Файл на сервере',
    'URL address' => 'URL адрес',
    'File URL' => 'URL адрес файла',
    'Icon URL' => 'URL адрес значка',
    'Anchor' => 'Якорь',
    'A bookmark with a unique name at a specific place on a web page, designed to create a transition to it via a link' 
        => 'Закладка с уникальным именем на определенном месте веб-страницы, предназначенная для создания перехода к ней по ссылке',
    'Icon' => 'Значок',
    'Open in new window' => 'Открыть в новом окне',
    'Specifies a value for the rel attribute of menu items. Most often, nofollow or noindex is used for search engine optimization purposes.' 
        => 'Указывает значение атрибуту rel пункта меню. Чаще всего используют nofollow или noindex в целях поисковой оптимизации.',
    // ItemForm: поля / rel 
    '[None]' => '[ без выбора ]',
    'Link to site archive' => 'Ссылка на архив сайта',
    'Link to the page about the author on the same domain' => 'Ссылка на страницу об авторе на том же домене',
    'Permalink to a section or post' => 'Постоянная ссылка на раздел или запись',
    'Link to first page' => 'Ссылка на первую страницу',
    'Link to help document' => 'Ссылка на документ со справкой',
    'Link to content' => 'Ссылка на содержание',
    'Link to last page' => 'Ссылка на последнюю страницу',
    'Link to license agreement or copyright page' => 'Ссылка на страницу с лицензионным соглашением или авторскими правами',
    'Link to the author\'s page on another domain' => 'Ссылка на страницу автора на другом домене',
    'Link to next page or section' => 'Ссылка на следующую страницу или раздел',
    'Do not send by reference TIC and PR' => 'Не передавать по ссылке ТИЦ и PR',
    'Do not pass HTTP headers by reference' => 'Не передавать по ссылке HTTP-заголовки',
    'Specifies that the specified resource should be pre-cache' => 'Указывает, что надо заранее кэшировать указанный ресурс',
    'Link to the previous page or section' => 'Ссылка на предыдущую страницу или раздел',
    'Search link' => 'Ссылка на поиск',
    'Add link to browser favorites' => 'Добавить ссылку в избранное браузера',
    'Indicates that the label (tag) is related to the current document' => 'Указывает, что метка (тег) имеет отношение к текущему документу',
    'Link to parent page' => 'Ссылка на родительскую страницу'
];

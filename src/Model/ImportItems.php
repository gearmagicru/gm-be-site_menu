<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\SiteMenu\Model;

/**
 * Импорт элементов (пуктов) меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu\Model
 * @since 1.0
 */
class Import extends \Gm\Import\Import
{
    /**
     * {@inheritdoc}
     */
    protected string $modelClass = '\Gm\Backend\SiteMenu\Model\Item';

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            // идентификатор элемента
            'id' => [
                'field' => 'id', 
                'type' => 'int'
            ],
            // идентификатор родительского элемента
            'parent_id' => [
                'field' => 'parent_id', 
                'type'  => 'int'
            ],
            // идентификатор меню (которому принадлежит элемент)
            'menu_id' => [
                'field' => 'menu_id', 
                'type'  => 'int'
            ],
            // идентификатор записи компонента (определяется типом элемента)
            'link_id' => [
                'field' => 'link_id', 
                'type'  => 'int'
            ],
            // идентификатор языка
            'language_id' => [
                'field' => 'language_id', 
                'type'  => 'int'
            ],
            // тип элемента: якорь, файл на сервере...
            'type' => [
                'field'  => 'type',
                'length' => 50,
                'trim'   => true
            ],
            // количество элементов на уровень ниже
            'count' => [
                'field' => 'count', 
                'type'  => 'int'
            ],
            // порядковый номер
            'index' => [
                'field' => 'index', 
                'type'  => 'int'
            ],
            // название
            'name' => [
                'field'  => 'name',
                'length' => 255,
                'trim'   => true
            ],
            // описание
            'description' => [
                'field' => 'description',
                'trim'  => true
            ],
            // заголовок (при наведении курсора на элемент)
            'title' => [
                'field'  => 'title',
                'length' => 255,
                'trim'   => true
            ],
            // CSS класс элемента
            'css' => [
                'field' => 'css',
                'length' => 50,
                'trim'   => true
            ],
            // атрибут "rel"
            'rel' => [
                'field' => 'rel',
                'length' => 50,
                'trim'   => true
            ],
            // якорь или URL-адрес
            'url' => [
                'field' => 'url',
                'length' => 255,
                'trim'   => true
            ],
            // открыть URL-адрес в новом окне
            'external_url' => [
                'field' => 'external_url', 
                'type'  => 'int'
            ],
            // URL-адрес значка элемента
            'image_url' => [
                'field' => 'image_url',
                'length' => 255,
                'trim'   => true
            ],
            // видимость элемента
            'visible' => [
                'field' => 'visible', 
                'type' => 'int'
            ]
        ];
    }
}

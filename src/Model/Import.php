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
 * Импорт меню.
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
    protected string $modelClass = '\Gm\Backend\SiteMenu\Model\Menu';

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            // идентификатор
            'id' => ['field' => 'id', 'type' => 'int'],
            // название
            'name' => ['field' => 'name'],
            // описание
            'description' => ['field' => 'description'],
            // видимость
            'visible' => ['field' => 'visible', 'type' => 'int']
        ];
    }
}

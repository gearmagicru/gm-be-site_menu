<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\SiteMenu\Model;

use Gm\Panel\Data\Model\Combo\ComboModel;

/**
 * Модель данных выпадающего списка меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu\Model
 * @since 1.0
 */
class MenuCombo extends ComboModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'tableName'  => '{{menu}}',
            'primaryKey' => 'id',
            'searchBy'   => 'name',
            'order'      => ['name' => 'ASC'],
            'fields'     => [
                ['id'],
                ['name']
            ]
        ];
    }
}
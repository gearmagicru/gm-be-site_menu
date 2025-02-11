<?php
/**
 * Модуль веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\SiteMenu;

use Gm;

/**
 * Модуль сайта меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu
 * @since 1.0
 */
class Module extends \Gm\Panel\Module\Module
{
    /**
     * {@inheritdoc}
     */
    public string $id = 'gm.be.site_menu';

    /**
     * {@inheritdoc}
     */
    public function controllerMap(): array
    {
        return [
            'items' => 'ItemsGrid',
            'item'  => 'ItemForm',
        ];
    }

    public function isWidgetRequest(): bool
    {
        return Gm::$app->request->getQuery('widget', 0, 'int') > 0;
    }
}

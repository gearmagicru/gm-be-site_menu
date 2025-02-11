<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\SiteMenu\Model;

use Gm;
use Gm\Panel\Data\Model\FormModel;

/**
 * Модель данных профиля пункта меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu\Model
 * @since 1.0
 */
class ItemsGridRow extends FormModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'useAudit'   => false,
            'tableName'  => '{{menu_items}}',
            'primaryKey' => 'id',
            'fields'     => [
                ['name'],
                ['menu_id', 'alias' => 'menuId'],
                ['visible', 'alias' => 'asVisible']
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_BEFORE_SAVE, function ($isInsert) {
                if (!$isInsert) {
                    $visible = (int) $this->asVisible;
                    // показать, что меню видимо если хотя бы один пункт меню видим
                    if ($visible > 0) {
                        /** @var null|Menu */
                        $menu = $this->module->getModel('Menu');
                        if ($menu) {
                            $menu = $menu->get($this->menuId);
                            if ($menu) {
                                $menu->visible = 1;
                                $menu->save();
                            }
                        }
                    }
                }
            })
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                /** @var \Gm\Panel\Http\Response $response */
                $response = $this->response();
                if ($message['success']) {
                    $visible = (int) $this->asVisible;
                    $message['message'] = $this->module->t('Menu item "{0}" - ' . ($visible > 0 ? 'show' : 'hide'), [$this->name]);
                    $message['title']   = $this->t($visible > 0 ? 'Show' : 'Hide');
                }
                // всплывающие сообщение
                $response
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
            });
    }
}

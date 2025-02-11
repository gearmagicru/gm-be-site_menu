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
 * Модель данных профиля записи пункта меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu\Model
 * @since 1.0
 */
class GridRow extends FormModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'tableName'  => '{{menu}}',
            'primaryKey' => 'id',
            'fields'     => [
                ['id'],
                ['visible'],
                ['name']
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
                    /** @var null|Item $menuItems */
                    $menuItems = $this->module->getModel('Item');
                    if ($menuItems) {
                        $visible = (int) $this->visible;
                        // если меню показано, то показать все пункты меню
                        if ($visible > 0)
                            $menuItems->showAll($this->id);
                        // если меню скрыто, то скрыть все пункты меню
                        else
                            $menuItems->hideAll($this->id);
                    }
                }
            })
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                $visible = (int) $this->visible;
                // Если значение `visible` отличается от устанавливаемого значения, то `$result = 1`. 
                // В остальных случаях  `$result = 0`, т.к. значение уже установлено и это считается
                // ошибкой. Чтобы не было такой ошибки, переопределяем `$message`.
                $message = [
                    'success' => true,
                    'message' => $this->module->t('Menu "{0}" - ' . ($visible > 0 ? 'show' : 'hide'), [$this->name]),
                    'title'   => $this->t($visible > 0 ? 'Show' : 'Hide'),
                    'type'    => 'accept'
                ];
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
            });
    }

    /**
     * Показывает меню.
     * 
     * @return $this
     */
    public function show(): static
    {
        $this->visible = 1;
        return $this;
    }

    /**
     * Скрывает меню.
     * 
     * @return $this
     */
    public function hide(): static
    {
        $this->visible = 0;
        return $this;
    }
}

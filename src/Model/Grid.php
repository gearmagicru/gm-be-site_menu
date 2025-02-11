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
use Gm\Panel\Data\Model\GridModel;

/**
 * Модель данных списка меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu\Model
 * @since 1.0
 */
class Grid extends GridModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'useAudit'   => false,
            'tableName'  => '{{menu}}',
            'primaryKey' => 'id',
            'fields'     => [
                ['name'], // заголовок
                ['description'], // описание
                ['visible'], // видимость
            ],
            'order' => [
                'name' => 'DESC'
            ],
            'dependencies' => [
                'deleteAll' => ['{{menu_items}}'],
                'delete' => [
                    '{{menu_items}}' => ['menu_id' => 'id']
                ]
            ],
            'resetIncrements' => ['{{menu}}', '{{menu_items}}']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_DELETE, function ($someRecords, $result, $message) {
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $this->module->t('Deleting a menu'), $message['type']);
                /** @var \Gm\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRow(array &$row): void
    {
        // заголовок контекстного меню записи
        $row['popupMenuTitle'] = $row['name'];
        // маршрут к пунктам меню
        $row['itemsRoute'] = Gm::alias('@match', '/items/view?menu=' . $row['id']);
    }
}

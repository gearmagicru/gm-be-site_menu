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
 * Модель данных профиля меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu\Model
 * @since 1.0
 */
class Form extends FormModel
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
                [
                    'name', 'title' => 'Name'
                ],
                [
                    'description', 'title' => 'Description'
                ],
                [
                    'visible', 'title' => 'Visible'
                ],
            ],
            'uniqueFields' => ['name'],
            'dependencies' => [
                'delete'    => [
                    '{{menu_items}}'  => ['menu_id' => 'id']
                ]
            ],
            // правила форматирования полей
            'formatterRules' => [
                [['name', 'description'], 'safe'],
                [['visible'], 'logic']
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
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                /** @var \Gm\Panel\Controller\FormController $controller */
                $controller = $this->controller();
                /** @var \Gm\Panel\Http\Response $response */
                $response = $this->response();
                // всплывающие сообщение
                $response
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                // обновить список
                $controller->cmdReloadGrid();
            })
            ->on(self::EVENT_AFTER_DELETE, function ($result, $message) {
                /** @var \Gm\Panel\Controller\FormController $controller */
                $controller = $this->controller();
                /** @var \Gm\Panel\Http\Response $response */
                $response = $this->response();
                // всплывающие сообщение
                $response
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                // обновить список
                $controller->cmdReloadGrid();
            });
    }
}

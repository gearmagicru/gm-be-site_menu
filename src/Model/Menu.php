<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\SiteMenu\Model;

use Gm\Db\ActiveRecord;

/**
 * Активная запись меню сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu\Model
 * @since 1.0
 */
class Menu extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function primaryKey(): string
    {
        return 'id';
    }

    /**
     * {@inheritdoc}
     */
    public function tableName(): string
    {
        return '{{menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'id'          => 'id', // идентификатор меню
            'name'        => 'name', // название 
            'description' => 'description', // описание
            'visible'     => 'visible' // видимость
        ];
    }

    /**
     * Возвращает запись по указанному значению первичного ключа.
     * 
     * @see ActiveRecord::selectByPk()
     * 
     * @param int|string $id Идентификатор записи.
     * 
     * @return null|Menu Активная запись при успешном запросе, иначе `null`.
     */
    public function get(mixed $identifier): ?static
    {
        return $this->selectByPk($identifier);
    }

    /**
     * Удаляет все записи.
     * 
     * @throws \Gm\Db\Adapter\Driver\Exception\CommandException Невозможно выполнить инструкцию SQL.
     */
    public function deleteAll()
    {
        $this->getDb()
            ->createCommand()
                ->truncateTable($this->tableName())
                ->execute();
    }
}

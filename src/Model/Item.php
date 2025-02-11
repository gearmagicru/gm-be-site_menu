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
use Gm\Db\Sql\Expression;

/**
 * Активная запись пункта меню сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu\Model
 * @since 1.0
 */
class Item extends ActiveRecord
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
        return '{{menu_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'id'          => 'id',
            'parentId'    => 'parent_id',
            'menuId'      => 'menu_id',
            'link_id'     => 'linkId',
            'type'        => 'type',
            'count'       => 'count',
            'index'       => 'index',
            'name'        => 'name',
            'description' => 'description',
            'title'       => 'title',
            'css'         => 'css',
            'rel'         => 'rel',
            'url'         => 'url',
            'aaa'         => 'aaa',
            'externalUrl' => 'external_url',
            'imageUrl'    => 'image_url',
            'visible'     => 'visible'
        ];
    }

    /**
     * Возвращает запись по указанному значению первичного ключа.
     * 
     * @see ActiveRecord::selectByPk()
     * 
     * @param mixed $id Идентификатор записи.
     * 
     * @return null|Item Активная запись при успешном запросе, иначе `null`.
     */
    public function get(mixed $identifier): ?static
    {
        return $this->selectByPk($identifier);
    }

        /**
     * Возвращает только одну активную запись по указанному значению первичного ключа.
     * 
     * @see ActiveRecord::selectOne()
     * 
     * @param mixed $value Значение первичного ключа таблицы.
     * 
     * @return null|ActiveRecord Активная запись при успешном запросе, иначе `null`.
     */
    public function getByIndex(int $index, int $parentId, int $menuId): ?static
    {
        return $this->selectOne(['index' => $index, 'parent_id' => $parentId, 'menu_id' => $menuId]);
    }

    /**
     * Скрывает все пункты указанно меню.
     * 
     * @param int $menuId Идентификатор меню.
     * 
     * @return false|int Если значение `false`, ошибка выполнения инструкции SQL. Иначе количество 
     *     скрытых пунктов меню.
     */
    public function hideAll(int $menuId): false|int
    {
        return $this->updateRecord(['visible' => 0], ['menu_id' => $menuId]);
    }

    /**
     * Скрывает пункт меню.
     * 
     * @return void
     */
    public function hide(): void
    {
        $this->visible = 0;
        $this->save();
    }

    /**
     * Показывает все пункты указанно меню.
     * 
     * @param int $menuId Идентификатор меню.
     * 
     * @return false|int Если значение `false`, ошибка выполнения инструкции SQL. Иначе количество 
     *     скрытых пунктов меню.
     */
    public function showAll(int $menuId): false|int
    {
        return $this->updateRecord(['visible' => 1], ['menu_id' => $menuId]);
    }

    /**
     * Показывает пункт меню.
     * 
     * @return void
     */
    public function show(): void
    {
        $this->visible = 1;
        $this->save();
    }

    /**
     * Перемещает пункт меню на уровень выше.
     * 
     * @return bool Если значение `true`, пункт меню успешно перемещен.
     */
    public function moveUp(): bool
    {
        $index = (int) $this->index;
        if ($index > 1) {
            $index = $index - 1;

            $prevItem = (new Item())->getByIndex($index, $this->parentId, $this->menuId);
            if ($prevItem) {
                $prevItem->index = $index + 1;
                if ($prevItem->save() === false) {
                    return false;
                }
            }

            $this->index = $index;
            return $this->save() === false ? false : true;
        }
        return true;
    }

    /**
     * Перемещает пункт меню на уровень ниже.
     * 
     * @return bool Если значение `true`, пункт меню успешно перемещен.
     */
    public function moveDown(): bool
    {
        $index = (int) $this->index;
        $index = $index + 1;

        $nextItem = (new Item())->getByIndex($index, $this->parentId, $this->menuId);
        if ($nextItem) {
            $nextItem->index = $index - 1;
            if ($nextItem->save() === false) {
                return false;
            }

            $this->index = $index;
            return $this->save() === false ? false : true;
        }
        return true;
    }

    /**
     * Перемещение пункта меню относительно выбраного пункта.
     * 
     * @param int $overNodeId Пункт меню, относительно которого происходит перемещение.
     * @param string $position Перемещение: after (после) или before (до) пункта меню $overNodeId.
     * 
     * @return bool Если значение `true`, пункт меню успешно перемещен.
     */
    public function move(int $overNodeId, string $position): bool
    {
        /** @var null|Item */
        $overNode = (new Item())->get($overNodeId);
        if ($overNode === null)  {
            return false;
        }

        // определяем, было ли перемещение к другому родителю
        if ($overNode->parentId != $this->parentId) {
            // т.к. узел ушел от своего родителя, то обновляем там индексы
            $this->updateRecord(
                ['index' => new Expression('`index` - 1')], 
                ['`index` > ' . $this->index . ' AND parent_id = ' .  $this->parentId]
            );

           if ($position === 'before') {
                $this->index = $overNode->index;
                $offsetIndex = $overNode->index - 1;
            } else
            if ($position === 'after') {
                $this->index = $overNode->index + 1;
                $offsetIndex = $overNode->index;
            }

            // т.к. узел пришел к новому родителя, то обновляем там индексы
            $this->updateRecord(
                ['index' => new Expression('`index` + 1')], 
                ['`index` > ' . $offsetIndex . ' AND parent_id = ' .  $overNode->parentId]
            );

            // обновляем количество пунктов у предыдущего родителя
            if ($this->parentId > 0)
                $this->updateRecord(['count' => new Expression('`count` - 1')], ['id' => $this->parentId]);

            // переносим узел новому родителю и указываем новый индекс
            $this->parentId = $overNode->parentId;
            if ($this->save() === false) {
                return false;
            }

            // обновляем количество пунктов у нового родителя
            if ($overNode->parentId > 0)
                $this->updateRecord(['count' => new Expression('`count` + 1')], ['id' => $overNode->parentId]);

        // если перемещение относительно родителя
        } else  {
            $oldIndex = $this->index;

            // если перенос снизу вверх
            if ($overNode->index < $oldIndex)  {
                if ($position === 'before') {
                    $this->index = $overNode->index;
                    $fromIndex = $overNode->index - 1;
                } else
                if ($position === 'after') {
                    $this->index = $overNode->index + 1;
                    $fromIndex = $overNode->index;
                }
                // обновляем индексы
                $this->updateRecord(
                    ['index' => new Expression('`index` + 1')], 
                    ['`index` > ' . $fromIndex . ' AND  `index` < ' . $oldIndex . ' AND parent_id = ' .  $this->parentId]
                );
            // если перенос свверху вниз
            } else {
                if ($position === 'before') {
                    $this->index = $overNode->index - 1;
                    $toIndex = $overNode->index;
                } else
                if ($position === 'after') {
                    $this->index = $overNode->index;
                    $toIndex = $overNode->index + 1;
                }
                $this->updateRecord(
                    ['index' => new Expression('`index` - 1')], 
                    ['`index` > ' . $oldIndex . ' AND  `index` < ' . $toIndex . ' AND parent_id = ' .  $this->parentId]
                );
            }

            // переносим узел 
            if ($this->save() === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Уменьшает порядковый номер пунктов меню на единицу следующих после текущего.
     * 
     * Применяется в пределах родительского узла.
     * 
     * @return void
     */
    public function decrementIndexAfter(): void
    {
        $index = $this->index > 0 ? $this->index - 1 : 0;

        $this->updateRecord(
            ['index' => new Expression('`index` - 1')], 
            ['`index` > ' . $index, 'parent_id' => $this->parentId]
        );
    }

    /**
     * Увеличивает порядковый номер пунктов меню на единицу следующих после текущего.
     * 
     * Применяется в пределах родительского узла.
     * 
     * @return void
     */
    public function incrementIndexAfter(): void
    {
        $index = $this->index > 0 ? $this->index - 1 : 0;

        $this->updateRecord(
            ['index' => new Expression('`index` + 1')], 
            ['`index` > ' . $index, 'parent_id' => $this->parentId]
        );
    }

    /**
     * Возвращает следующий порядковый номер в списке родителя.
     * 
     * @param int $parentId Идентификатор родителя.
     * 
     * @return int
     */
    public function getNextIndex(int $parentId): int
    {
        /** @var \Gm\Db\Sql\Select $select */
        $select = $this->select([new Expression('MAX(`index`)')], ['parent_id' => $parentId]);
        $index = $this
            ->getDb()
                ->createCommand($select)
                    ->queryScalar();
        $index = (int) $index;
        return $index + 1;
    }
}

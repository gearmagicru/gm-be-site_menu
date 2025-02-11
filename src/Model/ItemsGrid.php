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
use Gm\Db\Sql\Expression;
use Gm\Mvc\Module\BaseModule;
use Gm\Panel\Data\Model\AdjacencyGridModel;

/**
 * Модель данных списка меню.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu\Model
 * @since 1.0
 */
class ItemsGrid extends AdjacencyGridModel
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\SiteMenu\Module
     */
    public BaseModule $module;

    /**
     * {@inheritdoc}
     */
    public bool $collectRowsId = true;

    /**
     * Имена отношений к ссылке.
     * 
     * @var array
     */
    protected array $relNames = [];

    /**
     * Имена видов пунктов меню.
     * 
     * @var array
     */
    protected array $typeNames = [];

    /**
     * Языки.
     * 
     * @var array
     */
    protected array $languages = [];

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'useAudit'   => false,
            'tableName'  => '{{menu_items}}',
            'primaryKey' => 'id',
            'parentKey'  => 'parent_id',
            'countKey'   => 'count',
            'fields'     => [
                [
                    'parent_id',
                    'alias' => 'parentId'
                ],
                [
                    'menu_id',
                    'alias' => 'menuId'
                ],
                ['type'],
                [
                    'index',
                    'alias' => 'asIndex'
                ],
                ['name'],
                ['description'],
                [ // язык (динамическое поле)
                    'language', 
                    'direct' => 'language_id'
                ],
                ['title'],
                ['count', 'alias' => 'asCount'],
                ['css'],
                ['rel'],
                ['url'],
                [
                    'external_url',
                    'alias' => 'externalUrl'
                ],
                [
                    'image_url',
                    'alias' => 'asIcon'
                ],
                [
                    'visible',
                    'alias' => 'asVisible'
                ]
            ],
            'order' => [
                'index' => 'ASC'
            ],
            'resetIncrements' => ['{{menu}}'],
            'filter' => [
                'parentId' => ['operator' => '='],
                'language' => ['operator' => '=']
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
            ->on(self::EVENT_AFTER_DELETE, function ($someRecords, $result, $message) {
                /** @var int $menuId Идентификатор меню */
                $menuId = $this->getMenuId();
                $this->response()
                    ->meta
                        // всплывающие сообщение
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type'])
                        // обновить дерево
                        ->cmdReloadTreeGrid($this->module->viewId('items' . $menuId . '-grid'));
            })
            ->on(self::EVENT_AFTER_SET_FILTER, function ($filter) {
                /** @var int $menuId Идентификатор меню */
                $menuId = $this->getMenuId();
                $this->response()
                    ->meta
                        ->cmdReloadTreeGrid($this->module->viewId('items' . $menuId . '-grid'));
            });
    }

    /**
     * {@inheritdoc}
     */
    protected function validateFilterValue(string $field, mixed $value): mixed
    {
        if ($field === 'language') {
            return $value > 0 ? $value : false;
        }

        return parent::validateFilterValue($field, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeFetchRow(array &$row): void
    {
        // язык
        $languageSlug = null;
        if ($row['language_id']) {
            $language = $this->languages[(int) $row['language_id']] ?? null;
            if ($language) {
                $languageSlug = $language['slug'];
                $row['language'] = $language['shortName'] . ' (' . $languageSlug . ')';
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRow(array &$row): void
    {
        // заголовок контекстного меню записи
        $row['popupMenuTitle'] = $row['name'];
        // тип
        $type = $row['type'] ?? '';
        $row['typeName'] = $this->typeNames[$type] ?? $type;
        // отношение к ссылке
        $rel = $row['rel'] ?? '';
        $row['relName'] = $this->relNames[$rel] ?? $rel;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeDelete(bool $someRecords = true): bool
    {
        // если удаляется только выбранная запись
        if ($someRecords) {
            $rowsId = $this->getIdentifier();
            // убедились что идентификатор выбран
            if (isset($rowsId[0])) {
                /** @var null|Item $item Выбранный пункт меню */
                $item = (new Item())->get((int) $rowsId[0]);
                if ($item) {
                    // уменьшаем следующие индексы пунктов меню, чтобы был порядок
                    $item->decrementIndexAfter();
                }
            }
        }

        return parent::beforeDelete();
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSelect(mixed $command = null): void
    {
        // все доступные языки
        $this->languages = Gm::$app->language->available->getAllBy('code');
        // имена видов пунктов меню
        $this->typeNames = [
            'article' => $this->module->t('Article'),
            'category' => $this->module->t('Article category'),
            'link'     => $this->module->t('Arbitrary link'),
            'file'     => $this->module->t('File on the server'),
            'anchor'   => $this->module->t('Anchor'),
        ];
        // имена отношений к ссылке
        $this->relNames = [
            'archives' => $this->module->t('Link to site archive'),
            'author'   => $this->module->t('Link to the page about the author on the same domain'),
            'bookmark' => $this->module->t('Permalink to a section or post'),
            'first'    => $this->module->t('Link to first page'),
            'help'     => $this->module->t('Link to first page'),
            'index'    => $this->module->t('Link to content'),
            'last'     => $this->module->t('Link to last page'),
            'license'  => $this->module->t('Link to license agreement or copyright page'),
            'me'       => $this->module->t('Link to the author\'s page on another domain'),
            'next'     => $this->module->t('Link to next page or section'),
            'nofollow' => $this->module->t('Do not send by reference TIC and PR'),
            'noreferrer'=> $this->module->t('Do not pass HTTP headers by reference'),
            'prefetch' => $this->module->t('Specifies that the specified resource should be pre-cache'),
            'prev'     => $this->module->t('Link to the previous page or section'),
            'search'   => $this->module->t('Search link'),
            'sidebar'  => $this->module->t('Add link to browser favorites'),
            'tag'      => $this->module->t('Indicates that the label (tag) is related to the current document'),
            'up'       => $this->module->t('Link to parent page')
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function selectNodes(string|int $parentId = null): array
    {
        /** @var \Gm\Db\Sql\Select $select */
        $select = $this->builder()->select();
        $select
            ->columns(['*'])
            ->quantifier(new Expression('SQL_CALC_FOUND_ROWS'))
            ->from($this->tableName());

        /** @var int $menuId Идентификатор меню */
        $menuId = $this->getMenuId();
        if ($menuId) {
            $select->where(['menu_id' => $menuId]);
        }
        // все дочернии элементы
        $select->where([$this->parentKey() => $parentId ?: 0]);

        /** @var \Gm\Db\Adapter\Driver\AbstractCommand $command */
        $command = $this->buildQuery($select);
        $rows    = $this->fetchRows($command);
        $rows    = $this->afterFetchRows($rows);
        return $this->afterSelect($rows, $command);
    }

    /**
     * {@inheritdoc}
     */
    protected function deleteProcessCondition(array &$where): void
    {
        parent::deleteProcessCondition($where);

        $where['menu_id'] = $this->getMenuId();
    }

    /**
     * Возвращает идентификатор меню.
     * 
     * @return int
     */
    protected function getMenuId(): int
    {
        return Gm::$app->request->getQuery('menu', 0, 'int');
    }
}

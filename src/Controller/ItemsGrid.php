<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\SiteMenu\Controller;

use Gm;
use Gm\Helper\Url;
use Gm\Mvc\Module\BaseModule;
use Gm\Panel\Http\Response;
use Gm\Panel\Helper\ExtCombo;
use Gm\Panel\Helper\HtmlGrid;
use Gm\Panel\Widget\TabTreeGrid;
use Gm\Panel\Helper\ExtGridTree as ExtGrid;
use Gm\Panel\Helper\HtmlNavigator as HtmlNav;
use Gm\Panel\Controller\TreeGridController;
use Gm\Backend\SiteMenu\Model\Menu;

/**
 * Контроллер списка пунктов меню сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu\Controller
 * @since 1.0
 */
class ItemsGrid extends TreeGridController
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
    protected string $defaultModel = 'ItemsGrid';

    /**
     * Меню.
     * 
     * ItemsGrid::init()
     * 
     * @var array
     */
    protected array $menu;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_BEFORE_ACTION, function ($controller, $action, $result) {
                if ($action === 'view') {
                    /** @var Response $response */
                    $response = $this->getResponse();

                    /** @var int $menuId Идентификатор меню */
                    $menuId = Gm::$app->request->getQuery('menu');
                    if (empty($menuId)) {
                        $response
                            ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['menu']));
                        return $response;
                    }

                    /** @var null|Menu Меню */
                    $menu = (new Menu())->get($menuId);
                    if ($menu === null) {
                        $response
                            ->meta->error($this->t('Unable to determine menu'));
                        return $response;
                    }
                    $this->menu = $menu->getAttributes();
                }
            });
    }


    /**
     * {@inheritdoc}
     */
    public function createWidget(): TabTreeGrid
    {
       /** @var TabTreeGrid $tab Сетка данных в виде дерева (Gm.view.grid.Tree Gm JS) */
        $tab = parent::createWidget();

        // вкладка
        $tab->id = 'items' . $this->menu['id']; // => gm-site-menu-items{id}
        $tab->title = $this->module->t('Menu "{0}"', [$this->menu['name']]);
        $tab->tooltip['title'] = $tab->title;

        // столбцы (Gm.view.grid.Tree.columns GmJS)
        $tab->treeGrid->id = 'items' . $this->menu['id'] . '-grid';  // => gm-site-menu-items{id}-grid
        $tab->treeGrid->nodesDraggable = true;
        $tab->treeGrid->nodesDragConfig = [
            'allowParentInserts'  => true, 
            'dragText'            => '{0} выбранных пункта меню {1}'
        ];
        $tab->treeGrid->columns = [
            ExtGrid::columnAction(),
            [
                'text'      => '№',
                'tooltip'   => '#Sequence number in the list',
                'dataIndex' => 'asIndex',
                'filter'    => ['type' => 'string'],
                'sortable'  => false,
                'width'     => 67,
                'hidden'    => true
            ],
            [
                'text'      => 'ID',
                'dataIndex' => 'id',
                'filter'    => ['type' => 'numeric'],
                'sortable'  => false,
                'width'     => 75,
                'hidden'    => true
            ],
            [
                'xtype'     => 'treecolumn',
                'text'      => ExtGrid::columnInfoIcon($this->t('Name')),
                'cellTip'   => HtmlGrid::tags([
                    HtmlGrid::header('{name}'),
                    HtmlGrid::fieldLabel($this->t('Index number'), '{asIndex}'),
                    HtmlGrid::fieldLabel($this->t('Menu item ID'), '{id}'),
                    HtmlGrid::fieldLabel($this->t('Menu ID'), '{menuId}'),
                    HtmlGrid::fieldLabel($this->t('Number of menu subitems'), '{count}'),
                    HtmlGrid::fieldLabel($this->t('Description'), '{description}'),
                    HtmlGrid::fieldLabel($this->t('Title'), '{title}'),
                    HtmlGrid::fieldLabel($this->t('Class CSS'), '{css}'),
                    HtmlGrid::fieldLabel($this->t('Relationship to link'), '{relName}'),
                    HtmlGrid::fieldLabel($this->t('Type'), '{typeName}'),
                    HtmlGrid::fieldLabel($this->t('URL address'), '{url}'),
                    HtmlGrid::fieldLabel(
                        $this->t('Open in new window'),
                        HtmlGrid::tplChecked('externalUrl==1')
                    ),
                    HtmlGrid::fieldLabel(
                        $this->t('Visible on site'),
                        HtmlGrid::tplChecked('asVisible==1')
                    )
                ]),
                'dataIndex' => 'name',
                'filter'    => ['type' => 'string'],
                'sortable'  => false,
                'width'     => 200
            ],
            [
                'text'      => '#Description',
                'dataIndex' => 'description',
                'cellTip'   => '{description}',
                'filter'    => ['type' => 'string'],
                'sortable'  => false,
                'width'     => 200,
            ],
            [
                'text'      => '#Language',
                'dataIndex' => 'language',
                'sortable'  => false,
                'width'     => 120
            ],
            [
                'text'      => '#Title',
                'dataIndex' => 'title',
                'tooltip'   => '#The title attribute is the alternative title of the menu item that appears when you hover over it',
                'cellTip'   => '{title}',
                'filter'    => ['type' => 'string'],
                'sortable'  => false,
                'width'     => 150,
                'hidden'    => true
            ],
            [
                'text'      => '#Class CSS',
                'dataIndex' => 'css',
                'tooltip'   => '#Allows you to set an individual CSS class for a menu item. It is used to give a single item an individual style.',
                'filter'    => ['type' => 'string'],
                'sortable'  => false,
                'width'     => 110,
                'hidden'    => true
            ],
            [
                'text'      => '#Relationship to link',
                'dataIndex' => 'relName',
                'tooltip'   => '#Specifies a value for the rel attribute of menu items. Most often, nofollow or noindex is used for search engine optimization purposes.',
                'cellTip'   => '{relName}',
                'sortable'  => false,
                'width'     => 150,
                'hidden'    => true
            ],
            [
                'text'      => '#Type',
                'dataIndex' => 'typeName',
                'tooltip'   => '#Menu item type',
                'cellTip'   => '{typeName}',
                'sortable'  => false,
                'width'     => 150
            ],
            [
                'text'      => '#URL address',
                'dataIndex' => 'url',
                'cellTip'   => '{url}',
                'filter'    => ['type' => 'string'],
                'sortable'  => false,
                'width'     => 140,
                'hidden'    => true
            ],
            [
                'xtype' => 'templatecolumn',
                'align' => 'center',
                'tpl'   => HtmlGrid::a(
                    '', 
                    '{url}',
                    [
                        'title' => $this->t('Open the site page that the menu item refers to'),
                        'class' => 'g-icon g-icon-svg g-icon_size_14 g-icon-m_link g-icon-m_color_default g-icon-m_is-hover',
                        'target' => '_blank'
                    ]
                ),
                'sortable' => false,
                'width'    => 45
            ],
            [
                'text'      => ExtGrid::columnIcon('g-icon-m_nodes', 'svg'),
                'tooltip'   => '#Number of menu subitems',
                'align'     => 'center',
                'dataIndex' => 'asCount',
                'filter'    => ['type' => 'numeric'],
                'sortable'  => false,
                'width'     => 60
            ],
            [
                'text'      => ExtGrid::columnIcon('gm-site-menu__icon-openwindow', 'svg'),
                'xtype'     => 'g-gridcolumn-checker',
                'tooltip'   => '#Open in new window',
                'dataIndex' => 'externalUrl',
                'filter'    => ['type' => 'boolean'],
                'sortable'  => false,
                'hidden'    => true
            ],
            [
                'text'      => ExtGrid::columnIcon('g-icon-m_visible', 'svg'),
                'xtype'     => 'g-gridcolumn-switch',
                'tooltip'   => '#Visible on site',
                'selector'  => 'treepanel',
                'dataIndex' => 'asVisible',
                'filter'    => ['type' => 'boolean'],
                'sortable'  => false
            ]
        ];

        // панель инструментов (Gm.view.grid.Tree.tbar GmJS)
        $tab->treeGrid->tbar = [
            'padding' => 1,
            'items'   => ExtGrid::buttonGroups([
                'edit' => [
                    'items' => [
                        // инструмент "Добавить"
                        'add' => [
                            'iconCls'     => 'g-icon-svg gm-site-menu__icon-item-add',
                            'handlerArgs' => ['route' => Gm::alias('@match', '/item?menu=' . $this->menu['id'])],
                            'tooltip'     => '#Adding a new item'
                        ],
                        // инструмент "Удалить"
                        'delete' => [
                            'iconCls' => 'g-icon-svg gm-site-menu__icon-item-delete',
                            'tooltip' => '#Deleting selected items'
                        ],
                        'cleanup' => [
                            'tooltip' => '#Delete all menu items'
                        ],
                        '-',
                        'edit',
                        'select',
                        '-',
                        'refresh'
                    ]
                ],
                'columns',
                'search' => [
                    'items' => [
                        'help' => [
                            'subject' => 'items-grid'
                        ],
                        'search',
                        // инструмент "Фильтр"
                        'filter' => ExtGrid::popupFilter([
                            ExtCombo::trigger(
                                '#Language', 'language', 'language', true, ['languages/trigger/combo', BACKEND], 
                                ['editable' => false]
                            )
                        ], [
                            'action' => Url::toMatch('items/filter?menu=' . $this->menu['id'])
                        ])
                    ]
                ]
            ])
        ];

        // контекстное меню записи (Gm.view.grid.Tree.popupMenu GmJS)
        $tab->treeGrid->popupMenu = [
            'items' => [
                [
                    'text'        => '#Edit item',
                    'iconCls'     => 'g-icon-svg g-icon-m_edit g-icon-m_color_default',
                    'handlerArgs' => [
                        'route'   => Gm::alias('@match', '/item/view/{id}?menu=' . $this->menu['id']),
                        'pattern' => 'grid.popupMenu.activeRecord'
                    ],
                    'handler' => 'loadWidget'
                ],
                '-',
                [
                    'text'        => '#Add subitem',
                    'iconCls'     => 'g-icon-svg gm-site-menu__icon-subitem-add',
                    'handlerArgs' => [
                        'route'   => Gm::alias('@match', '/item/view?node={id}&menu=' . $this->menu['id']),
                        'pattern' => 'grid.popupMenu.activeRecord'
                    ],
                    'handler' => 'loadWidget'
                ],
                '-',
                [
                    'text'        => '#Move one level up',
                    'iconCls'     => 'g-icon-svg gm-site-menu__icon-subitem-moveup',
                    'handlerArgs' => [
                        'route'   => Gm::alias('@match', '/items/moveup/{id}?menu=' . $this->menu['id']),
                        'pattern' => 'grid.popupMenu.activeRecord'
                    ],
                    'handler' => 'loadWidget'
                ],
                [
                    'text'        => '#Move one level down',
                    'iconCls'     => 'g-icon-svg gm-site-menu__icon-subitem-movedown',
                    'handlerArgs' => [
                        'route'   => Gm::alias('@match', '/items/movedown/{id}?menu=' . $this->menu['id']),
                        'pattern' => 'grid.popupMenu.activeRecord'
                    ],
                    'handler' => 'loadWidget'
                ],
            ]
        ];

        // поле аудита записи
        $tab->treeGrid->logField = 'name';
        // плагины сетки
        $tab->treeGrid->plugins = 'gridfilters';
        // класс CSS применяемый к элементу body сетки
        $tab->treeGrid->bodyCls = 'g-grid_background';
        // количество строк в сетке
        $tab->treeGrid->multiSelect = false;
        $tab->treeGrid->store->pageSize = 50;
        $tab->treeGrid->columnLines  = true;
        $tab->treeGrid->rowLines     = true;
        $tab->treeGrid->lines        = true;
        $tab->treeGrid->singleExpand = false;
        $tab->treeGrid->store->folderSort = false;
        $tab->treeGrid->router->setAll([
            'rules' => [
                'clear'      => '{route}/clear?menu=' . $this->menu['id'],
                'delete'     => '{route}/delete?menu=' . $this->menu['id'],
                'data'       => '{route}/data?menu=' . $this->menu['id'],
                'deleteRow'  => '{route}/delete/{id}?menu=' . $this->menu['id'],
                'updateRow'  => '{route}/update/{id}',
                'move'  => '{route}/move/{id}'
            ],
            'route' => Gm::alias('@backend', '/site-menu/items')
        ]);

        // панель навигации (Gm.view.navigator.Info GmJS)
        $tab->navigator->info['tpl'] = HtmlNav::tags([
            HtmlNav::header('<span class="g-navigator_menu-header {icon}">{name}</span>'),
            ['fieldset',
                [
                    HtmlNav::fieldLabel($this->t('Index number'), '{asIndex}'),
                    HtmlNav::fieldLabel($this->t('Menu item ID'), '{id}'),
                    HtmlNav::fieldLabel($this->t('Menu ID'), '{menuId}'),
                    HtmlNav::fieldLabel($this->t('Number of menu subitems'), '{count}'),
                    HtmlNav::fieldLabel($this->t('Description'), '{description}'),
                    HtmlNav::fieldLabel($this->t('Title'), '{title}'),
                    HtmlNav::fieldLabel($this->t('Class CSS'), '{css}'),
                    HtmlNav::fieldLabel($this->t('Relationship to link'), '{relName}'),
                    HtmlNav::fieldLabel($this->t('Type'), '{typeName}'),
                    HtmlNav::fieldLabel($this->t('URL address'), '{url}'),
                    HtmlNav::fieldLabel(
                        $this->t('Open in new window'),
                        HtmlNav::tplChecked('externalUrl==1')
                    ),
                    HtmlNav::fieldLabel(
                        $this->t('Visible on site'),
                        HtmlNav::tplChecked('asVisible==1')
                    ),
                    HtmlNav::widgetButton(
                        $this->t('Edit item'),
                        ['route' => Gm::alias('@match', '/item/view/{id}?menu=' . $this->menu['id']), 'long' => true],
                        ['title' => $this->t('Edit item')]
                    ),
                    HtmlNav::widgetButton(
                        $this->t('Add subitem'),
                        ['route' => Gm::alias('@match', '/item/view?node={id}&menu=' . $this->menu['id']), 'long' => true],
                        ['title' => $this->t('Edit item')]
                    ),
                    HtmlNav::linkButton(
                        $this->t('Open the site page'),
                        ['long' => true],
                        [
                            'title'  => $this->t('Open the site page that the menu item refers to'), 
                            'href'   => '{url}', 
                            'target' => '_blank'
                        ]
                    )
                ]
            ]
        ]);

        $tab
            ->addCss('/grid.css')
            ->addRequire('Gm.view.grid.column.Switch');
        return $tab;
    }

    /**
     * Действие "moveup" перемещает пункт меню на уровень вверх.
     * 
     * @return Response
     */
    public function moveupAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var \Gm\Backend\SiteMenu\Model\Item|null $model Модель данных */
        $model = $this->getModel('Item');
        if ($model === null) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"', ['Item']));
            return $response;
        }

        /** @var int $itemId Идентификатор выбранного пункта меню */
        $itemId = (int) Gm::$app->router->get('id');
        if (empty($itemId)) {
            $response
                ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['id']));
            return $response;
        }

        if ($this->useAppEvents) {
            Gm::$app->doEvent($this->makeAppEventName(), [$this, $itemId]);
        }

        /** @var \Gm\Backend\SiteMenu\Model\Item|null $item */
        $item = $model->get($itemId);
        if ($item === null) {
            $response
                ->meta->error(Gm::t(BACKEND, 'The item you selected does not exist or has been deleted'));
            return $response;
        }

        // перемещение на уровень выше
        if ($item->moveUp() === false) {
            $response
                ->meta->error($this->module->t('Menu item move error'));
            return $response;
        } else {
            $response
                // обновить дерево
                ->meta->cmdReloadTreeGrid($this->module->viewId('items' . $item->menuId . '-grid'));
        }
        return $response;
    }

    /**
     * Действие "movedown" перемещает пункт меню на уровень вниз.
     * 
     * @return Response
     */
    public function movedownAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var \Gm\Backend\SiteMenu\Model\Item|null $model Модель данных */
        $model = $this->getModel('Item');
        if ($model === null) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"', ['Item']));
            return $response;
        }

        /** @var int $itemId Идентификатор выбранного пункта меню */
        $itemId = (int) Gm::$app->router->get('id');
        if (empty($itemId)) {
            $response
                ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['id']));
            return $response;
        }

        if ($this->useAppEvents) {
            Gm::$app->doEvent($this->makeAppEventName(), [$this, $itemId]);
        }

        /** @var \Gm\Backend\SiteMenu\Model\Item|null $item */
        $item = $model->get($itemId);
        if ($item === null) {
            $response
                ->meta->error(Gm::t(BACKEND, 'The item you selected does not exist or has been deleted'));
            return $response;
        }

        // перемещение на уровень ниже
        if ($item->moveDown() === false) {
            $response
                ->meta->error($this->module->t('Menu item move error'));
            return $response;
        } else {
            $response
                // обновить дерево
                ->meta->cmdReloadTreeGrid($this->module->viewId('items' . $item->menuId . '-grid'));
        }
        return $response;
    }

    /**
     * Действие "move" перемещает пункты меню относительно других пунктов.
     * 
     * @return Response
     */
    public function moveAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var \Gm\Backend\SiteMenu\Model\Item|null $model Модель данных */
        $model = $this->getModel('Item');
        if ($model === null) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"', ['Item']));
            return $response;
        }

        /** @var int $itemId Идентификатор выбранного пункта меню */
        $itemId = (int) Gm::$app->router->get('id');
        if (empty($itemId)) {
            $response
                ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['id']));
            return $response;
        }

        /** @var int $moveTo Идентификатор пункта, куда перемещают */
        $moveTo = Gm::$app->request->getPost('moveTo', 0, 'int');
        if (empty($moveTo)) {
            $response
                ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['moveTo']));
            return $response;
        }

        /** @var string $position Позиция */
        $position = Gm::$app->request->getPost('position', '', 'string');
        if (empty($position)) {
            $response
                ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['position']));
            return $response;
        }

        if ($this->useAppEvents) {
            Gm::$app->doEvent($this->makeAppEventName(), [$this, $itemId]);
        }

        /** @var \Gm\Backend\SiteMenu\Model\Item|null $item */
        $item = $model->get($itemId);
        if ($item === null) {
            $response
                ->meta->error(Gm::t(BACKEND, 'The item you selected does not exist or has been deleted'));
            return $response;
        }

        // перемещение
        if ($item->move($moveTo, $position) === false) {
            $response
                ->meta->error($this->module->t('Menu item move error'));
            return $response;
        }
        return $response;
    }
}

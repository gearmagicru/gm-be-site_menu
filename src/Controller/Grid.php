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
use Gm\Panel\Http\Response;
use Gm\Panel\Widget\TabGrid;
use Gm\Panel\Helper\ExtGrid;
use Gm\Panel\Helper\HtmlGrid;
use Gm\Panel\Helper\HtmlNavigator as HtmlNav;
use Gm\Panel\Controller\GridController;

/**
 * Контроллер списка меню сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu\Controller
 * @since 1.0
 */
class Grid extends GridController
{
    /**
     * {@inheritdoc}
     */
    public function createWidget(): TabGrid
    {
        /** @var TabGrid $tab Сетка данных (Gm.view.grid.Grid GmJS) */
        $tab = parent::createWidget();

        // столбцы (Gm.view.grid.Grid.columns GmJS)
        $tab->grid->columns = [
            ExtGrid::columnNumberer(),
            ExtGrid::columnAction(),
            [
                'text'      => 'ID',
                'tooltip'   => '#Menu identifier',
                'dataIndex' => 'id',
                'filter'    => ['type' => 'numeric'],
                'width'     => 70
            ],
            [
                'text'    => ExtGrid::columnInfoIcon($this->t('Name')),
                'cellTip' => HtmlGrid::tags([
                    HtmlGrid::header('{name:ellipsis(50)}'),
                    HtmlGrid::fieldLabel($this->t('Description'), '{description}'),
                    HtmlGrid::fieldLabel(
                        $this->t('Visible on site'),
                        HtmlGrid::tplChecked('visible==1')
                    )
                ]),
                'dataIndex' => 'name',
                'filter'    => ['type' => 'string'],
                'width'     => 220
            ],
            [
                'text'    => '#Description',
                'dataIndex' => 'description',
                'filter'    => ['type' => 'string'],
                'width'     => 200
            ],
            [
                'xtype'   => 'g-gridcolumn-control',
                'width'   => 50,
                'tooltip' => '#Site menu items',
                'items'   => [
                    [
                        'iconCls'   => 'g-icon-svg g-icon_size_20 gm-site-menu__icon-items',
                        'dataIndex' => 'itemsRoute',
                        'tooltip'   => '#Go to adding / editing menu items',
                        'handler'   => 'loadWidgetFromCell'
                    ],
                ]
            ],
            [
                'text'      => ExtGrid::columnIcon('g-icon-m_visible', 'svg'),
                'xtype'     => 'g-gridcolumn-switch',
                'tooltip'   => '#Menu visibility',
                'selector'  => 'grid',
                'collectData' => ['name'],
                'dataIndex' => 'visible'
            ]
        ];

        // панель инструментов (Gm.view.grid.Grid.tbar GmJS)
        $tab->grid->tbar = [
            'padding' => 1,
            'items'   => ExtGrid::buttonGroups([
                'edit' => [
                    'items' => [
                        // инструмент "Добавить"
                        'add' => [
                            'iconCls' => 'g-icon-svg gm-site-menu__icon-add',
                            'tooltip' => '#Adding a new menu',
                            'caching' => false
                        ],
                        // инструмент "Удалить"
                        'delete' => [
                            'iconCls' => 'g-icon-svg gm-site-menu__icon-delete',
                            'tooltip' => '#Deleting selected menus'
                        ],
                        'cleanup' => [
                            'tooltip' => '#Delete all menus'
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
                        'help',
                        'search'
                    ]
                ]
            ])
        ];

        // контекстное меню записи (Gm.view.grid.Grid.popupMenu GmJS)
        $tab->grid->popupMenu = [
            'cls'        => 'g-gridcolumn-popupmenu',
            'titleAlign' => 'center',
            'width'      => 150,
            'items'      => [
                [
                    'text'        => '#Edit menu',
                    'iconCls'     => 'g-icon-svg g-icon-m_edit g-icon-m_color_default',
                    'handlerArgs' => [
                          'route'   => Gm::alias('@match', '/form/view/{id}'),
                          'pattern' => 'grid.popupMenu.activeRecord'
                      ],
                      'handler' => 'loadWidget'
                ],
                '-',
                [
                    'text'        => '#Site menu items',
                    'iconCls'     => 'g-icon-svg gm-site-menu__icon-items',
                    'handlerArgs' => [
                          'route'   => '{itemsRoute}',
                          'pattern' => 'grid.popupMenu.activeRecord'
                      ],
                      'handler' => 'loadWidget'
                ],
            ]
        ];

        // 2-й клик на строке сетки
        $tab->grid->rowDblClickConfig = [
            'allow' => true,
            'route' => Gm::alias('@match', '/form/view/{id}')
        ];
        // сортировка сетки по умолчанию
        $tab->grid->sorters = [
           ['property' => 'name', 'direction' => 'ASC']
        ];
        // количество строк в сетке
        $tab->grid->store->pageSize = 50;
        // поле аудита записи
        $tab->grid->logField = 'header';
        // плагины сетки
        $tab->grid->plugins = 'gridfilters';
        // класс CSS применяемый к элементу body сетки
        $tab->grid->bodyCls = 'g-grid_background';

        // панель навигации (Gm.view.navigator.Info GmJS)
        $tab->navigator->info['tpl'] = HtmlNav::tags([
            HtmlNav::header('{name}'),
            ['div', '{description}', ['align' => 'center']],
            HtmlNav::fieldLabel(
                $this->t('Visible on site'),
                HtmlNav::tplChecked('visible==1')
            ),
            ['fieldset',
                [
                    HtmlNav::widgetButton(
                        $this->t('Edit menu'),
                        ['route' => Gm::alias('@match', '/form/view/{id}'), 'long' => true],
                        ['title' => $this->t('Edit menu')]
                    ),
                    HtmlNav::widgetButton(
                        $this->t('Site menu items'),
                        ['route' => '{itemsRoute}', 'long' => true],
                        ['title' => $this->t('Edit menu')]
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
     * Действие "show" показывает меню.
     * 
     * @return Response
     */
    public function showAction(): Response
    {
        /** @var \Gm\Panel\Http\Response $response */
        $response = $this->getResponse();

        /** @var null|\Gm\Backend\SiteMenu\Model\GridRow $model */
        $model = $this->getModel($this->defaultModel . 'Row');
        if ($model === null) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"', [$this->defaultModel . 'Row']));
            return $response;
        }

        /** @var null|\Gm\Backend\SiteMenu\Model\GridRow $form Запись по идентификатору в запросе */
        $form = $model->get();
        if ($form === null) {
            $response
                ->meta->error(Gm::t(BACKEND, 'No data to perform action'));
            return $response;
        }

        // показать и сохранить меню
        $form
            ->show()
            ->save();
        return $response;
    }

    /**
     * Действие "hide" скрывает меню.
     * 
     * @return Response
     */
    public function hideAction(): Response
    {
        /** @var \Gm\Panel\Http\Response $response */
        $response = $this->getResponse();

        /** @var null|\Gm\Backend\SiteMenu\Model\GridRow $model */
        $model = $this->getModel($this->defaultModel . 'Row');
        if ($model === null) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"', [$this->defaultModel . 'Row']));
            return $response;
        }

        /** @var null|\Gm\Backend\SiteMenu\Model\GridRow $form Запись по идентификатору в запросе */
        $form = $model->get();
        if ($form === null) {
            $response
                ->meta->error(Gm::t(BACKEND, 'No data to perform action'));
            return $response;
        }

        // скрыть и сохранить меню
        $form
            ->hide()
            ->save();
        return $response;
    }
}

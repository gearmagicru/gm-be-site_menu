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
use Gm\Panel\Widget\Form;
use Gm\Panel\Helper\ExtCombo;
use Gm\Panel\Widget\EditWindow;
use Gm\Backend\SiteMenu\Model\Item;
use Gm\Panel\Controller\FormController;

/**
 * Контроллер формы меню сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu\Controller
 * @since 1.0
 */
class ItemForm extends FormController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'ItemForm';

    /**
     * Атрибуты родительского узла.
     * 
     * @see ItemForm::init()
     * 
     * @var array
     */
    protected array $parentItem = [];

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_BEFORE_ACTION, function ($controller, $action, $result) {
                // интерфейс формы
                if ($action === 'view') {
                    /** @var int $parentId Идентификатор родительского узла */
                    $parentId = Gm::$app->request->get('node');
                    if ($parentId) {
                        /** @var null|Item $item */
                        $item = (new Item())->get($parentId);
                        if ($item  === null) {
                            $this->getResponse()
                                ->meta->error(Gm::t('app', 'Parameter passed incorrectly "{0}"', ['node']));
                            $result = false;
                            return;
                        }
                        $this->parentItem = $item->getAttributes();
                    }
                }

                // интерфейс формы, добавление пункта меню
                if ($action === 'view' || $action === 'add') {
                    /** @var int $menuId Идентификатор меню */
                    $this->menuId = Gm::$app->request->getQuery('menu');
                    if (empty($this->menuId)) {
                        $this->getResponse()
                            ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['menu']));
                        $result = false;
                        return;
                    }
                }
            });
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): EditWindow
    {
        /** @var EditWindow $window */
        $window = parent::createWidget();

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->router->route = Gm::alias('@match', '/item');
        $window->form->router->rules = [
            'update' => '{route}/update/{id}?menu=' . $this->menuId,
            'delete' => '{route}/delete/{id}?menu=' . $this->menuId,
            'add'    => '{route}/add?menu=' . $this->menuId,
            'data'   => '{route}/data/{id}'
        ];
        $window->form->autoScroll = true;
        $window->form->controller = 'gm-be-site_menu-iform';
        $window->form->bodyPadding = 10;
        $window->form->defaults = [
            'labelAlign' => 'right',
            'labelWidth' => 140
        ];
        $window->form->loadJSONFile('/item-form', 'items', [
            '@parentId' => $this->parentItem ? $this->parentItem['id'] : 0,
            '@languagesCombobox' => ExtCombo::remote('#Language', 'language', [
                'proxy' => [
                    'url' =>  ['languages/trigger/combo', 'backend'],
                    'extraParams' => [
                        'combo'   => 'language'
                    ]
                ]
            ], [
                'xtype'    => 'g-field-combobox',
                'editable' => false
            ]),
            '@articlesCombobox' => ExtCombo::remote('#Article', 'articleId', [
                'proxy' => [
                    'url' =>  ['articles/trigger/combo', 'backend'],
                    'extraParams' => [
                        'combo'   => 'articles',
                        'noneRow' => 0
                    ]
                ]
            ], [
                'id'     => 'gm-be-menu__article',
                'xtype'  => 'g-field-combobox',
                'hidden' => true
            ]),
            '@categoriesCombobox' => ExtCombo::remote('#Article category', 'categoryId', [
                'proxy' => [
                    'url' =>  ['article-categories/trigger/combo', 'backend'],
                    'extraParams' => [
                        'combo'   => 'categories',
                        'noneRow' => 0
                    ]
                ]
            ], [
                'id'     => 'gm-be-menu__category',
                'xtype'  => 'g-field-combobox',
                'hidden' => true
            ])
        ]);
        // для переопределения "help"
        $window->form->setStateButtons(
            Form::STATE_UPDATE, 
            ['help' => ['subject' => 'item-form'], 'reset', 'save', 'delete', 'cancel']
        );
        $window->form->setStateButtons(
            Form::STATE_INSERT, 
            ['help' => ['subject' => 'item-form'], 'add', 'cancel']
        );

        // окно компонента (Ext.window.Window Sencha ExtJS)
        if ($this->parentItem) {
            $window->title    = $this->module->t('{subitem.title}', [$this->parentItem['name']]);
            $window->titleTpl = $window->title;
        } else {
            $window->title    = '#{item.title}';
            $window->titleTpl = '#{item.titleTpl}';
        }
        $window->width = 580;
        $window->autoHeight = true;
        $window->layout = 'fit';
        $window->resizable = false;
        $window
            ->setNamespaceJS('Gm.be.site_menu')
            ->addRequire('Gm.be.site_menu.ItemFormController');
        return $window;
    }
}

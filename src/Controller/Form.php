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
use Gm\Panel\Widget\EditWindow;
use Gm\Panel\Controller\FormController;

/**
 * Контроллер формы меню сайта.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\SiteMenu\Controller
 * @since 1.0
 */
class Form extends FormController
{
    /**
     * {@inheritdoc}
     */
    public function createWidget(): EditWindow
    {
        /** @var EditWindow $window */
        $window = parent::createWidget();

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->autoScroll = true;
        $window->form->bodyPadding = 10;
        $window->form->defaults = [
            'labelAlign' => 'right',
            'labelWidth' => 120
        ];
        $window->form->loadJSONFile('/form', 'items');

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->width = 500;
        $window->autoHeight = true;
        $window->layout = 'fit';
        $window->resizable = false;
        return $window;
    }
}

/*!
 * Контроллер формы пункта меню.
 * Модуль "Меню сайта".
 * Copyright 2015 Вeб-студия GearMagic. Anton Tivonenko <anton.tivonenko@gmail.com>
 * https://gearmagic.ru/license/
 */

Ext.define('Gm.be.site_menu.ItemFormController', {
    extend: 'Gm.view.form.PanelController',
    alias: 'controller.gm-be-site_menu-iform',

    /**
     * Выбор типа пункта меню.
     * @param {Ext.form.field.ComboBox} me
     * @param {String} newValue
     * @param {String} oldValue
      *@param {Object} eOpts
     */
    onTypeChange: function (me, newValue, oldValue, eOpts ) {
        let data = me.getStore().getData();
        data.each(function (item, index) {
            let field = Ext.getCmp(item.data.fieldId);

            if (newValue === item.id)
                field.show(); 
            else
                field.hide();
        });
    }
});

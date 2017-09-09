OfficeExt.window.ViewOrder = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('ms2_order') + ' #' + config.record['num'],
        width: 750,
        resizable: false,
        maximizable: false,
        collapsible: false,
    });
    OfficeExt.window.ViewOrder.superclass.constructor.call(this, config);
};
Ext.extend(OfficeExt.window.ViewOrder, OfficeExt.window.Default, {

    getFields: function (config) {
        var tabs = [{
            title: _('office_ms2_order'),
            hideMode: 'offsets',
            defaults: {msgTarget: 'under', border: false},
            items: this.getOrderFields(config)
        }, {
            title: _('office_ms2_order_products'),
            xtype: 'minishop2-grid-order-products',
            hideMode: 'offsets',
            order_id: config.order_id
        }];

        var address = this.getAddressFields(config);
        if (address.length > 0) {
            tabs.push({
                layout: 'form',
                title: _('office_ms2_address'),
                hideMode: 'offsets',
                defaults: {msgTarget: 'under', border: false},
                items: address
            });
        }

        return {
            xtype: 'modx-tabs',
            activeTab: config.activeTab || 0,
            bodyStyle: {background: 'transparent', padding: '10px', margin: 0},
            border: true,
            stateful: true,
            stateId: 'minishop2-window-order-details',
            stateEvents: ['tabchange'],
            getState: function () {
                return {activeTab: this.items.indexOf(this.getActiveTab())};
            },
            items: tabs
        }
    },

    getButtons: function () {
        return [{
            text: _('close'),
            scope: this,
            handler: function () {
                this.hide();
            }
        }];
    },

    getKeys: function () {
        return [];
    },

    getOrderFields: function (config) {
        var fields = [{
            xtype: 'hidden',
            name: 'id'
        }, {
            layout: 'column',
            defaults: {msgTarget: 'under', border: false},
            style: 'padding:15px 5px;text-align:center;',
            items: [{
                columnWidth: .5,
                layout: 'form',
                items: [{
                    xtype: 'displayfield',
                    name: 'fullname',
                    fieldLabel: _('office_ms2_customer'),
                    anchor: '100%',
                    style: 'font-size:1.1em;'
                }]
            }, {
                columnWidth: .5,
                layout: 'form',
                items: [{
                    xtype: 'displayfield',
                    name: 'cost',
                    fieldLabel: _('office_ms2_order_cost'),
                    anchor: '100%',
                    style: 'font-size:1.1em;'
                }]
            }]
        }];

        var all = {
            num: {style: 'font-size:1.1em;'},
            weight: {},
            createdon: {},
            updatedon: {},
            cart_cost: {},
            delivery_cost: {},
            status: {},
            delivery: {},
            payment: {}
        };

        var tmp = [];
        for (var i = 0; i < OfficeExt.config['order_form_fields'].length; i++) {
            var field = OfficeExt.config['order_form_fields'][i];
            if (all[field]) {
                Ext.applyIf(all[field], {
                    xtype: 'displayfield',
                    name: field,
                    fieldLabel: _('office_ms2_' + field)
                });
                all[field].anchor = '100%';
                tmp.push(all[field]);
            }
        }

        if (tmp.length > 0) {
            var add = {
                layout: 'column',
                xtype: 'fieldset',
                style: 'padding:15px 5px;text-align:center;',
                defaults: {msgTarget: 'under', border: false},
                items: [
                    {columnWidth: .33, layout: 'form', items: []},
                    {columnWidth: .33, layout: 'form', items: []},
                    {columnWidth: .33, layout: 'form', items: []}
                ]
            };
            for (i = 0; i < tmp.length; i++) {
                field = tmp[i];
                add.items[i % 3].items.push(field);
            }
            fields.push(add);
        }
        fields.push({xtype: 'minishop2-grid-order-logs', order_id: config.order_id});

        return fields;
    },

    getAddressFields: function () {
        var all = {
            receiver: {},
            phone: {},
            index: {},
            country: {},
            region: {},
            metro: {},
            building: {},
            city: {},
            street: {},
            room: {}
        };
        var fields = [];
        var tmp = [];
        for (var i = 0; i < OfficeExt.config['order_address_fields'].length; i++) {
            var field = OfficeExt.config['order_address_fields'][i];
            if (all[field]) {
                Ext.applyIf(all[field], {
                    xtype: 'displayfield',
                    name: 'addr_' + field,
                    fieldLabel: _('office_ms2_' + field)
                });
                all[field].anchor = '100%';
                tmp.push(all[field]);
            }
        }

        if (tmp.length > 0) {
            var add = {
                layout: 'column',
                defaults: {msgTarget: 'under', border: false},
                items: [
                    {columnWidth: .5, layout: 'form', items: []},
                    {columnWidth: .5, layout: 'form', items: []}
                ]
            };
            for (i = 0; i < tmp.length; i++) {
                field = tmp[i];
                add.items[i % 2].items.push(field);
            }
            fields.push(add);

            if (OfficeExt.config['order_address_fields'].in_array('comment')) {
                fields.push({
                    xtype: 'displayfield',
                    name: 'addr_comment',
                    fieldLabel: _('office_ms2_comment'),
                    anchor: '100%'
                });
            }
        }

        return fields;
    },

});
Ext.reg('minishop2-window-order-details', OfficeExt.window.ViewOrder);
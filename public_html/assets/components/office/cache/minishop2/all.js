OfficeExt.combo.OrderStatus = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        name: 'status',
        id: 'minishop2-combo-status',
        hiddenName: 'status',
        displayField: 'name',
        valueField: 'id',
        fields: ['id', 'name'],
        pageSize: 10,
        emptyText: _('office_ms2_combo_select_status'),
        url: OfficeExt.config.connector_url,
        baseParams: {
            action: 'minishop2/getStatus',
            combo: true,
            addall: config.addall || 0,
            order_id: config.order_id || 0,
            pageId: OfficeConfig.pageId
        },
        listeners: OfficeExt.combo.listeners_disable
    });
    OfficeExt.combo.OrderStatus.superclass.constructor.call(this, config);
};
Ext.extend(OfficeExt.combo.OrderStatus, MODx.combo.ComboBox);
Ext.reg('minishop2-combo-status', OfficeExt.combo.OrderStatus);
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
OfficeExt.grid.Logs = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        baseParams: {
            action: 'minishop2/getLog',
            order_id: config.order_id,
            type: 'status',
            pageId: OfficeConfig.pageId
        },
        pageSize: Math.round(OfficeExt.config['default_per_page'] / 6),
    });
    OfficeExt.grid.Logs.superclass.constructor.call(this, config);
};
Ext.extend(OfficeExt.grid.Logs, OfficeExt.grid.Default, {

    getTopBar: function () {
        return [];
    },

    getFields: function () {
        return ['timestamp', 'action', 'entry'];
    },

    getColumns: function () {
        return [{
            header: _('office_ms2_timestamp'),
            dataIndex: 'timestamp',
            sortable: true,
            renderer: this._formatDate,
            width: 100,
        }, {
            header: _('office_ms2_action'),
            dataIndex: 'action',
            width: 100,
        }, {
            header: _('office_ms2_entry'),
            dataIndex: 'entry',
            width: 100,
        }];
    },

    _formatDate: function (val) {
        return OfficeExt.utils.formatDate(val, OfficeExt.config.ms2_date_format);
    },

});
Ext.reg('minishop2-grid-order-logs', OfficeExt.grid.Logs);
OfficeExt.grid.Products = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        baseParams: {
            action: 'minishop2/getOrderProducts',
            order_id: config.order_id,
            pageId: OfficeConfig.pageId
        },
        pageSize: Math.round(OfficeExt.config['default_per_page'] / 4),
    });
    OfficeExt.grid.Products.superclass.constructor.call(this, config);
};
Ext.extend(OfficeExt.grid.Products, OfficeExt.grid.Default, {

    getTopBar: function () {
        return [];
    },

    getFields: function () {
        return OfficeExt.config['order_product_fields'];
    },

    getColumns: function () {
        var fields = {
            id: {hidden: true, sortable: true, width: 40},
            product_id: {hidden: true, sortable: true, width: 40},
            name: {header: _('office_ms2_product'), width: 100, renderer: this._productLink},
            product_weight: {header: _('office_ms2_product_weight'), width: 50},
            product_price: {header: _('office_ms2_product_price'), width: 50},
            article: {width: 50},
            weight: {sortable: true, width: 50},
            price: {sortable: true, width: 50},
            count: {sortable: true, width: 50},
            cost: {width: 50},
            options: {width: 100}
        };

        var columns = [];
        for (var i = 0; i < OfficeExt.config['order_product_fields'].length; i++) {
            var field = OfficeExt.config['order_product_fields'][i];
            if (fields[field]) {
                Ext.applyIf(fields[field], {
                    header: _('office_ms2_' + field),
                    dataIndex: field
                });
                columns.push(fields[field]);
            }
            else if (/^option_/.test(field)) {
                columns.push(
                    {header: _(field.replace(/^option_/, 'office_ms2_')), dataIndex: field, width: 50}
                );
            }
            else if (/^product_/.test(field)) {
                columns.push(
                    {header: _(field.replace(/^product_/, 'office_ms2_')), dataIndex: field, width: 75}
                );
            }
        }

        return columns;
    },

    _productLink: function (val, cell, row) {
        if (!val) {
            return '';
        }
        else if (!row.data['url']) {
            return val;
        }
        var url = row.data['url'];

        return '<a href="' + url + '" target="_blank" class="ms2-link">' + val + '</a>'
    },

});
Ext.reg('minishop2-grid-order-products', OfficeExt.grid.Products);
OfficeExt.grid.Orders = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        baseParams: {
            action: 'minishop2/getOrders',
            pageId: OfficeConfig.pageId
        },
        multi_select: true,
    });
    OfficeExt.grid.Orders.superclass.constructor.call(this, config);
    // Set sort info
    this.getStore().sortInfo = {
        field: 'createdon',
        direction: 'DESC'
    };
};
Ext.extend(OfficeExt.grid.Orders, OfficeExt.grid.Default, {
    windows: {},

    getFields: function () {
        return OfficeExt.config['order_grid_fields'];
    },

    getTopBar: function () {
        return [
            '->', {
                xtype: 'minishop2-combo-status',
                width: 200,
                addall: true,
                listeners: {
                    select: {fn: this._filterStatus, scope: this}
                }
            },
            '-',
            this.getSearchField(250)
        ];
    },

    getColumns: function () {
        var all = {
            id: {width: 50, hidden: true},
            //user_id: {width: 50, hidden: true},
            createdon: {width: 75, sortable: true, renderer: this._formatDate},
            updatedon: {width: 75, sortable: true, renderer: this._formatDate},
            num: {width: 50, sortable: true},
            cost: {width: 75, sortable: true, renderer: this._renderCost},
            cart_cost: {width: 75, sortable: true},
            delivery_cost: {width: 75, sortable: true},
            weight: {width: 50, sortable: true},
            status: {width: 75, sortable: true},
            delivery: {width: 75, sortable: true},
            payment: {width: 75, sortable: true},
            //address: {width: 50, sortable: true}
            //context: {width: 50, sortable: true},
            customer: {width: 150, sortable: true},
            receiver: {width: 150, sortable: true},
            actions: {width: 50, renderer: OfficeExt.utils.renderActions, id: 'actions'}
        };

        var columns = [];
        for (var i = 0; i < OfficeExt.config.order_grid_fields.length; i++) {
            var field = OfficeExt.config.order_grid_fields[i];
            if (all[field]) {
                Ext.applyIf(all[field], {
                    header: _('office_ms2_' + field),
                    dataIndex: field
                });
                columns.push(all[field]);
            }
        }

        return columns;
    },

    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.viewOrder(grid, e, row);
            }
        };
    },

    viewOrder: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        var id = this.menu.record.id;

        var mask = new Ext.LoadMask(this.getEl());
        mask.show();
        MODx.Ajax.request({
            url: OfficeExt.config.connector_url,
            params: {
                action: 'minishop2/getOrder',
                id: id,
                pageId: OfficeConfig.pageId
            },
            listeners: {
                success: {
                    fn: function (r) {
                        mask.hide();
                        var w = Ext.getCmp('minishop2-window-order-details');
                        if (w) {
                            w.hide();
                        }

                        w = MODx.load({
                            xtype: 'minishop2-window-order-details',
                            id: 'minishop2-window-order-details',
                            record: r.object,
                            order_id: id,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    }, scope: this
                                },
                            }
                        });
                        w.fp.getForm().reset();
                        w.fp.getForm().setValues(r.object);
                        w.show(Ext.isIE ? null : e.target);
                    }, scope: this
                },
                failure: function () {
                    mask.hide();
                }
            }
        });
    },

    repeatOrder: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        var id = this.menu.record.id;

        var mask = new Ext.LoadMask(this.getEl());
        mask.show();
        MODx.msg.confirm({
            title: _('office_ms2_warning'),
            text: _('office_ms2_repeat_confirm'),
            url: OfficeExt.config.connector_url,
            params: {
                action: 'minishop2/repeatOrder',
                id: id,
            },
            listeners: {
                success: {
                    fn: function (res) {
                        mask.hide();
                        if (res.object && !Ext.isEmpty(res.object.redirect)) {
                            document.location = res.object.redirect;
                        } else if (res.data && !Ext.isEmpty(res.data.redirect)) {
                            document.location = res.data.redirect;
                        } else {
                            this.refresh();
                        }
                    }, scope: this
                },
                cancel: {
                    fn: function () {
                        mask.hide();
                    }
                },
                failure: {
                    fn: function () {
                        mask.hide();
                    }
                },
            }
        });
    },

    removeOrder: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        var id = this.menu.record.id;

        MODx.msg.confirm({
            title: _('office_ms2_warning'),
            text: _('office_ms2_remove_confirm'),
            url: OfficeExt.config.connector_url,
            params: {
                action: 'minishop2/removeOrder',
                id: id,
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                },
            }
        });
    },

    _filterStatus: function (cb) {
        this.getStore().baseParams['status'] = cb.value;
        this.getBottomToolbar().changePage(1);
    },

    _formatDate: function (val) {
        return OfficeExt.utils.formatDate(val, OfficeExt.config.ms2_date_format);
    },

    _renderCost: function (v, md, rec) {
        return rec.data.type && rec.data.type == 1
            ? '-' + v
            : v;
    },

});
Ext.reg('minishop2-grid-orders', OfficeExt.grid.Orders);
OfficeExt.panel.Orders = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        layout: 'anchor',
        defaults: {border: false, autoHeight: true},
        hideMode: 'offsets',
        border: false,
        cls: 'main-wrapper',
        items: [{
            xtype: 'minishop2-grid-orders',
        }]
    });
    OfficeExt.panel.Orders.superclass.constructor.call(this, config);
};
Ext.extend(OfficeExt.panel.Orders, MODx.Panel);
Ext.reg('minishop2-panel-orders', OfficeExt.panel.Orders);
Ext.onReady(function () {
    var grid = new OfficeExt.panel.Orders();
    grid.render('office-minishop2-grid');

    var preloader = document.getElementById('office-preloader');
    if (preloader) {
        preloader.parentNode.removeChild(preloader);
    }
});
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
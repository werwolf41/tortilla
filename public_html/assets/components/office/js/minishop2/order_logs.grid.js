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
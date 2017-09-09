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
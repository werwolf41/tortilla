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
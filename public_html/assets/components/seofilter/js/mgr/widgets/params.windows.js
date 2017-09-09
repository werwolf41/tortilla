seoFilter.window.CreateParam = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'seofilter-param-window-create';
	}
	Ext.applyIf(config, {
		title: _('seofilter_param_create'),
		width: 550,
		autoHeight: true,
		url: seoFilter.config.connector_url,
		action: 'mgr/param/create',
		fields: this.getFields(config),
		keys: [{
			key: Ext.EventObject.ENTER, shift: true, fn: function () {
				this.submit()
			}, scope: this
		}]
	});
    seoFilter.window.CreateParam.superclass.constructor.call(this, config);
};
Ext.extend(seoFilter.window.CreateParam, MODx.Window, {

	getFields: function (config) {
		return [{
			xtype: 'textfield',
			fieldLabel: _('seofilter_param_name'),
			name: 'name',
			id: config.id + '-name',
			anchor: '99%',
			allowBlank: false
		}, {
            xtype: 'seofilter-combo-param-type',
            fieldLabel: _('seofilter_param_type'),
            name: 'type',
            hiddenName: 'type',
            id: config.id + '-type',
            anchor: '99%',
            allowBlank: false
        }];
	},

	loadDropZones: function() {
	}

});
Ext.reg('seofilter-param-window-create', seoFilter.window.CreateParam);


seoFilter.window.UpdateParam = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'seofilter-param-window-update';
	}
	Ext.applyIf(config, {
		title: _('seofilter_param_update'),
		width: 550,
		autoHeight: true,
		url: seoFilter.config.connector_url,
		action: 'mgr/param/update',
		fields: this.getFields(config),
		keys: [{
			key: Ext.EventObject.ENTER, shift: true, fn: function () {
				this.submit()
			}, scope: this
		}]
	});
    seoFilter.window.UpdateParam.superclass.constructor.call(this, config);
};
Ext.extend(seoFilter.window.UpdateParam, MODx.Window, {

	getFields: function (config) {
		return [{
			xtype: 'hidden',
			name: 'id',
			id: config.id + '-id'
		}, {
			xtype: 'textfield',
			fieldLabel: _('seofilter_param_name'),
			name: 'name',
			id: config.id + '-name',
			anchor: '99%',
			allowBlank: false
		}, {
            xtype: 'seofilter-combo-param-type',
            fieldLabel: _('seofilter_param_type'),
            name: 'type',
            hiddenName: 'type',
            id: config.id + '-type',
            anchor: '99%',
            allowBlank: false
        }];
	},

	loadDropZones: function() {
	}

});
Ext.reg('seofilter-param-window-update', seoFilter.window.UpdateParam);
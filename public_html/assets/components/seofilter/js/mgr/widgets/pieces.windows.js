seoFilter.window.CreatePiece = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'seofilter-piece-window-create';
	}
	Ext.applyIf(config, {
		title: _('seofilter_piece_create'),
		width: 550,
		autoHeight: true,
		url: seoFilter.config.connector_url,
		action: 'mgr/piece/create',
		fields: this.getFields(config),
		keys: [{
			key: Ext.EventObject.ENTER, shift: true, fn: function () {
				this.submit()
			}, scope: this
		}]
	});
    seoFilter.window.CreatePiece.superclass.constructor.call(this, config);
};
Ext.extend(seoFilter.window.CreatePiece, MODx.Window, {

	getFields: function (config) {
		return [{
			xtype: 'seofilter-combo-param',
			fieldLabel: _('seofilter_piece_param'),
			name: 'param',
            hiddenName: 'param',
			id: config.id + '-param',
			anchor: '99%',
			allowBlank: false
		},{
            xtype: 'textfield',
            fieldLabel: _('seofilter_piece_value'),
            name: 'value',
            id: config.id + '-value',
            anchor: '99%',
            allowBlank: false
        },{
            xtype: 'textfield',
            fieldLabel: _('seofilter_piece_alias'),
            name: 'alias',
            id: config.id + '-alias',
            anchor: '99%',
            allowBlank: true
        },{
            xtype: 'textfield',
            fieldLabel: _('seofilter_piece_correction'),
            name: 'correction',
            id: config.id + '-correction',
            anchor: '99%',
            allowBlank: true
        }];
	},

	loadDropZones: function() {
	}

});
Ext.reg('seofilter-piece-window-create', seoFilter.window.CreatePiece);


seoFilter.window.UpdatePiece = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'seofilter-piece-window-update';
	}
	Ext.applyIf(config, {
		title: _('seofilter_piece_update'),
		width: 550,
		autoHeight: true,
		url: seoFilter.config.connector_url,
		action: 'mgr/piece/update',
		fields: this.getFields(config),
		keys: [{
			key: Ext.EventObject.ENTER, shift: true, fn: function () {
				this.submit()
			}, scope: this
		}]
	});
    seoFilter.window.UpdatePiece.superclass.constructor.call(this, config);
};
Ext.extend(seoFilter.window.UpdatePiece, MODx.Window, {

	getFields: function (config) {
		return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id'
        },{
            xtype: 'seofilter-combo-param',
            fieldLabel: _('seofilter_piece_param'),
            name: 'param',
            hiddenName: 'param',
            id: config.id + '-param',
            anchor: '99%',
            allowBlank: false
        },{
            xtype: 'textfield',
            fieldLabel: _('seofilter_piece_value'),
            name: 'value',
            id: config.id + '-value',
            anchor: '99%',
            allowBlank: false
        },{
            xtype: 'textfield',
            fieldLabel: _('seofilter_piece_alias'),
            name: 'alias',
            id: config.id + '-alias',
            anchor: '99%',
            allowBlank: true
        },{
            xtype: 'textfield',
            fieldLabel: _('seofilter_piece_correction'),
            name: 'correction',
            id: config.id + '-correction',
            anchor: '99%',
            allowBlank: true
        }];
	},

	loadDropZones: function() {
	}

});
Ext.reg('seofilter-piece-window-update', seoFilter.window.UpdatePiece);
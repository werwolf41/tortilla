seoFilter.window.getPieceContentWindowFields = function (config, isCreate) {
    var availableFields = {
        tab_general: {
            resource_id: {xtype: 'seofilter-combo-category', anchor: '99%',allowBlank: false, hiddenName: 'resource_id' /*, value: config.record.parent,*/ },
            //resource_id: {xtype: 'textfield', anchor: '99%',allowBlank: false},
            alias: {xtype: 'textfield', anchor: '99%', allowBlank: false },
            pagetitle: {xtype: 'textfield', anchor: '99%', allowBlank: true }
        },
        tab_text1: {
            text1: {xtype: 'textarea', anchor: '99%', allowBlank: true, height: 350 }
        },
        tab_text2: {
            text2: {xtype: 'textarea', anchor: '99%', allowBlank: true, height: 350 }
        },
        tab_seo: {
            title: {xtype: 'textfield', anchor: '99%', allowBlank: true },
            keywords: {xtype: 'textfield', anchor: '99%', allowBlank: true },
            description: {xtype: 'textarea', anchor: '99%', allowBlank: true }
        }
    };

    var tabs = [];

    for(var tab_name in availableFields) {
        var fields = [];
        for (var field in availableFields[tab_name]){
            Ext.applyIf(availableFields[tab_name][field], {
                fieldLabel: _('seofilter_piece_content_' + field),
                name: field,
                id: config.id + '-' + field
            });
            fields.push(availableFields[tab_name][field]);
        }

        var tab = {
            title: _('seofilter_piece_content_' + tab_name),
            layout: 'anchor',
            items: [{
                layout: 'form',
                cls: 'modx-panel',
                items: [fields]
            }]
        };
        tabs.push(tab);
    }

    var result = [];
    if(!isCreate){
        result.push({ xtype: 'hidden', name: 'id', id: config.id + '-id' });
    }

    result.push({
        xtype: 'modx-tabs',
        defaults: {border: false, autoHeight: true},
        deferredRender: false,
        border: true,
        hideMode: 'offsets',
        items: [tabs]
    });

    return result;
}

seoFilter.window.CreatePieceContent = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'seofilter-piece-content-window-create';
	}
	Ext.applyIf(config, {
		title: _('seofilter_piece_content_create'),
		width: 550,
		autoHeight: true,
		url: seoFilter.config.connector_url,
		action: 'mgr/piece-content/create',
		fields: seoFilter.window.getPieceContentWindowFields(config, true),
		keys: [{
			key: Ext.EventObject.ENTER, shift: true, fn: function () {
				this.submit()
			}, scope: this
		}]
	});
    seoFilter.window.CreatePieceContent.superclass.constructor.call(this, config);
};
Ext.extend(seoFilter.window.CreatePieceContent, MODx.Window, {});
Ext.reg('seofilter-piece-content-window-create', seoFilter.window.CreatePieceContent);


seoFilter.window.UpdatePieceContent = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'seofilter-piece-content-window-update';
	}
	Ext.applyIf(config, {
		title: _('seofilter_piece_content_update'),
		width: 550,
		autoHeight: true,
		url: seoFilter.config.connector_url,
		action: 'mgr/piece-content/update',
		fields: seoFilter.window.getPieceContentWindowFields(config, false),
		keys: [{
			key: Ext.EventObject.ENTER, shift: true, fn: function () {
				this.submit()
			}, scope: this
		}]
	});
    seoFilter.window.UpdatePieceContent.superclass.constructor.call(this, config);
};
Ext.extend(seoFilter.window.UpdatePieceContent, MODx.Window, {});
Ext.reg('seofilter-piece-content-window-update', seoFilter.window.UpdatePieceContent);
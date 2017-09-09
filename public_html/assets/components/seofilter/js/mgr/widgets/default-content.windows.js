seoFilter.window.getDefaultContentWindowFields = function (config, isCreate) {
    var availableFields = {
        tab_general: {
            resource_id: {xtype: 'seofilter-combo-category', anchor: '99%',allowBlank: false, hiddenName: 'resource_id' /*, value: config.record.parent,*/ },
            active: {xtype: 'xcheckbox', anchor: '99%', allowBlank: true },
            children: {xtype: 'xcheckbox', anchor: '99%', allowBlank: true },
            priority: {xtype: 'textfield', anchor: '99%', allowBlank: true },
        },
        tab_text1: {
            text1: {xtype: 'textarea', anchor: '99%', allowBlank: true, height: 350 }
        },
        tab_text2: {
            text2: {xtype: 'textarea', anchor: '99%', allowBlank: true, height: 350 }
        }
    };

    var tabs = [];

    for(var tab_name in availableFields) {
        var fields = [];
        for (var field in availableFields[tab_name]){
            Ext.applyIf(availableFields[tab_name][field], {
                fieldLabel: _('seofilter_default_content_' + field),
                name: field,
                id: config.id + '-' + field
            });
            fields.push(availableFields[tab_name][field]);
        }

        var tab = {
            title: _('seofilter_default_content_' + tab_name),
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

seoFilter.window.CreateDefaultContent = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'seofilter-default-content-window-create';
	}
	Ext.applyIf(config, {
		title: _('seofilter_default_content_create'),
		width: 550,
		autoHeight: true,
		url: seoFilter.config.connector_url,
		action: 'mgr/default-content/create',
		fields: seoFilter.window.getDefaultContentWindowFields(config, true),
		keys: [{
			key: Ext.EventObject.ENTER, shift: true, fn: function () {
				this.submit()
			}, scope: this
		}]
	});
    seoFilter.window.CreateDefaultContent.superclass.constructor.call(this, config);
};
Ext.extend(seoFilter.window.CreateDefaultContent, MODx.Window, {});
Ext.reg('seofilter-default-content-window-create', seoFilter.window.CreateDefaultContent);


seoFilter.window.UpdateDefaultContent = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'seofilter-default-content-window-update';
	}
	Ext.applyIf(config, {
		title: _('seofilter_default_content_update'),
		width: 550,
		autoHeight: true,
		url: seoFilter.config.connector_url,
		action: 'mgr/default-content/update',
		fields: seoFilter.window.getDefaultContentWindowFields(config, false),
		keys: [{
			key: Ext.EventObject.ENTER, shift: true, fn: function () {
				this.submit()
			}, scope: this
		}]
	});
    seoFilter.window.UpdateDefaultContent.superclass.constructor.call(this, config);
};
Ext.extend(seoFilter.window.UpdateDefaultContent, MODx.Window, {});
Ext.reg('seofilter-default-content-window-update', seoFilter.window.UpdateDefaultContent);
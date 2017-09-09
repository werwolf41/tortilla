easyComm.window.getThreadWindowFields = function (config, isCreate) {
    var availableFields = {
        resource: {xtype: 'ec-combo-resource', anchor: '99%',allowBlank: false},
        name: {xtype: 'textfield', anchor: '99%',allowBlank: false},
        title: {xtype: 'textfield', anchor: '99%',allowBlank: true},
        extended: {xtype: 'textarea', anchor: '99%',allowBlank: true},
        count: {xtype: 'displayfield', anchor: '99%'},
        message_last: {xtype: 'displayfield', anchor: '99%'},
        message_last_date: {xtype: 'displayfield', anchor: '99%'},
        rating_simple: {xtype: 'displayfield', anchor: '99%'},
        rating_wilson: {xtype: 'displayfield', anchor: '99%'}
    }

    var hideInCreationMode = ['count', 'rating_simple', 'rating_wilson', 'message_last', 'message_last_date'];

    var fields = [];
    if(!isCreate){
        fields.push({ xtype: 'hidden', name: 'id', id: config.id + '-id' });
    }
    for (var i = 0; i < easyComm.config.thread_window_fields.length; i++) {
        var field = easyComm.config.thread_window_fields[i];
        if(isCreate && hideInCreationMode.in_array(field)) {
            continue;
        }
        if (availableFields[field]) {
            Ext.applyIf(availableFields[field], {
                fieldLabel: _('ec_thread_' + field),
                name: field,
                id: config.id + '-' + field
            });
            fields.push(availableFields[field]);
        }
    }
    return fields;
}

easyComm.window.CreateThread = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'ec-thread-window-create';
    }
    Ext.applyIf(config, {
        title: _('ec_thread_create'),
        width: 550,
        autoHeight: true,
        url: easyComm.config.connector_url,
        action: 'mgr/thread/create',
        fields: easyComm.window.getThreadWindowFields(config, true),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    easyComm.window.CreateThread.superclass.constructor.call(this, config);
};
Ext.extend(easyComm.window.CreateThread, MODx.Window, {});
Ext.reg('ec-thread-window-create', easyComm.window.CreateThread);


easyComm.window.UpdateThread = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'ec-thread-window-update';
    }
    Ext.applyIf(config, {
        title: _('ec_thread_update'),
        width: 550,
        autoHeight: true,
        url: easyComm.config.connector_url,
        action: 'mgr/thread/update',
        fields: easyComm.window.getThreadWindowFields(config, false),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    easyComm.window.UpdateThread.superclass.constructor.call(this, config);
};
Ext.extend(easyComm.window.UpdateThread, MODx.Window, {});
Ext.reg('ec-thread-window-update', easyComm.window.UpdateThread);
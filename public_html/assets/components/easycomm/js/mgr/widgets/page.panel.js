easyComm.panel.Page = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',

        hideMode: 'offsets',
        items: [/*{
            html: '<p>' + _('ec') + '</p>',
            cls: 'panel-desc',
            style: {margin: '15px 0'}
        }, */{
            xtype: 'modx-tabs',
            id: 'ec-page-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('ec_messages'),
                layout: 'anchor',
                items: [{
                    html: _('ec_messages_intro_msg'),
                    cls: 'panel-desc'
                }, {
                    xtype: 'ec-grid-messages',
                    cls: 'main-wrapper',
                    record: config.record
                }]
            }, {
                title: _('ec_threads'),
                layout: 'anchor',
                items: [{
                    html: _('ec_threads_intro_msg'),
                    cls: 'panel-desc'
                }, {
                    xtype: 'ec-grid-threads',
                    cls: 'main-wrapper',
                    record: config.record
                }]
            }]
        }]
    });
    easyComm.panel.Page.superclass.constructor.call(this, config);
};
Ext.extend(easyComm.panel.Page, MODx.Panel);
Ext.reg('ec-panel-page', easyComm.panel.Page);
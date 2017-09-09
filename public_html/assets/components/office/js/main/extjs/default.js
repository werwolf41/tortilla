var OfficeExt = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        config: {
            connector_url: OfficeConfig.actionUrl
        }
    });
    OfficeExt.superclass.constructor.call(this, config);
};
Ext.extend(OfficeExt, Ext.Component, {
    page: {},
    window: {},
    grid: {},
    tree: {},
    panel: {},
    combo: {},
    config: {},
    view: {},
    keymap: {},
    plugin: {},
    utils: {},
});
Ext.reg('office', OfficeExt);

OfficeExt = new OfficeExt();
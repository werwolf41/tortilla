easyComm.page.Home = function (config) {
	config = config || {};
	Ext.applyIf(config, {
		components: [{
			xtype: 'ec-panel-home',
            renderTo: 'ec-panel-home-div'
		}]
	});
	easyComm.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(easyComm.page.Home, MODx.Component);
Ext.reg('ec-page-home', easyComm.page.Home);
seoFilter.page.Home = function (config) {
	config = config || {};
	Ext.applyIf(config, {
		components: [{
			xtype: 'seofilter-panel-home',
            renderTo: 'seofilter-panel-home-div'
		}]
	});
    seoFilter.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(seoFilter.page.Home, MODx.Component);
Ext.reg('seofilter-page-home', seoFilter.page.Home);
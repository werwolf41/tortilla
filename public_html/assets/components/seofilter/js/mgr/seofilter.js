var seoFilter = function (config) {
	config = config || {};
    seoFilter.superclass.constructor.call(this, config);
};
Ext.extend(seoFilter, Ext.Component, {
	page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('seoFilter', seoFilter);

seoFilter = new seoFilter();
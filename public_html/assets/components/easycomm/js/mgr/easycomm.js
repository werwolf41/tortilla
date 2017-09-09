var easyComm = function (config) {
	config = config || {};
	easyComm.superclass.constructor.call(this, config);
};
Ext.extend(easyComm, Ext.Component, {
	page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}, plugin: {}
});
Ext.reg('easyComm', easyComm);

easyComm = new easyComm();
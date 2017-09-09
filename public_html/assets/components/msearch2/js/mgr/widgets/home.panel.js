mSearch2.panel.Home = function(config) {
	config = config || {};
	Ext.apply(config,{
		border: false
		,baseCls: 'modx-formpanel'
		,items: [{
			html: '<h2>'+_('msearch2')+'</h2>'
			,border: false
			,cls: 'modx-page-header container'
		},{
			xtype: 'modx-tabs'
			,defaults: { border: false ,autoHeight: true }
			,border: true
			,hideMode: 'offsets'
			,stateful: true
			,stateId: 'msearch2-home-tabpanel'
			,stateEvents: ['tabchange']
			,getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};}
			,items: [{
				title: _('mse2_tab_search')
				,layout: 'anchor'
				,items: [{
					html: _('mse2_tab_search_intro')
					,border: false
					,bodyCssClass: 'panel-desc'
				},{
					xtype: 'msearch2-grid-search'
					,cls: 'main-wrapper'
				}]
			},{
				title: _('mse2_tab_index')
				,layout: 'anchor'
				,items: [{
					html: _('mse2_tab_index_intro')
					,border: false
					,bodyCssClass: 'panel-desc'
				},{
					xtype: 'msearch2-form-index'
					,cls: 'main-wrapper'
				}]
			},{
				title: _('mse2_tab_queries')
				,layout: 'anchor'
				,items: [{
					html: _('mse2_tab_queries_intro')
					,border: false
					,bodyCssClass: 'panel-desc'
				},{
					xtype: 'msearch2-grid-queries'
					,cls: 'main-wrapper'
				}]
				/*
				,listeners: {
					activate : {fn: function() {
						Ext.getCmp('msearch2-grid-queries').refresh();
					}, scope: this}
				}
				*/
			},{
				title: _('mse2_tab_aliases')
				,layout: 'anchor'
				,items: [{
					html: _('mse2_tab_aliases_intro')
					,border: false
					,bodyCssClass: 'panel-desc'
				},{
					xtype: 'msearch2-grid-aliases'
					,cls: 'main-wrapper'
				}]
			},{
				title: _('mse2_tab_dictionaries')
				,layout: 'anchor'
				,items: [{
					html: _('mse2_tab_dictionaries_intro')
					,border: false
					,bodyCssClass: 'panel-desc'
				},{
					xtype: 'msearch2-grid-dictionaries'
					,cls: 'main-wrapper'
				}]
			}]
		}]
	});
	mSearch2.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(mSearch2.panel.Home,MODx.Panel);
Ext.reg('msearch2-panel-home',mSearch2.panel.Home);

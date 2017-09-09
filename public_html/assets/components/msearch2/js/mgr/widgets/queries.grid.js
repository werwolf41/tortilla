mSearch2.grid.Queries = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		id: 'msearch2-grid-queries'
		,url: mSearch2.config.connector_url
		,baseParams: {
			action: 'mgr/query/getlist'
		}
		,fields: ['query','found','quantity']
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,columns: [
			{header: _('mse2_query'), dataIndex: 'query', width: 150, sortable: true}
			,{header: _('mse2_query_quantity'), dataIndex: 'quantity', width: 50, sortable: true}
			,{header: _('mse2_query_found'), dataIndex: 'found', width: 50, sortable: true}
		]
		,tbar: [{
			xtype: 'button'
			,text: '<i class="' + (MODx.modx23 ? 'icon icon-trash-o' : 'fa fa-trash-o') + '"></i> ' + _('mse2_query_remove_all')
			,listeners: {
				click: {fn: this.removeQueries, scope:this}
			}
		},'->',{
			xtype: 'textfield'
			,name: 'query'
			,width: 200
			,id: 'mse2-queries-search'
			,emptyText: _('mse2_query_search')
			,listeners: {
				render: {fn:function(tf) {tf.getEl().addKeyListener(Ext.EventObject.ENTER,function() {this.Search(tf);},this);},scope:this}
			}
		},{
			xtype: 'button'
			,text: '<i class="' + (MODx.modx23 ? 'icon icon-times' : 'fa fa-times') + '"></i>'
			,listeners: {
				click: {fn: this.clearFilter, scope: this}
			}
		}]
	});
	mSearch2.grid.Queries.superclass.constructor.call(this,config);
};
Ext.extend(mSearch2.grid.Queries,MODx.grid.Grid, {
	windows: {}

	,getMenu: function() {
		var m = [];
		var cls = MODx.modx23 ? 'icon icon-' : 'fa fa-';
		m.push({
			text: '<i class="' + cls + 'trash-o x-menu-item-icon"></i> ' + _('mse2_query_remove')
			,handler: this.removeQuery
		});
		this.addContextMenuItem(m);
	}

	,removeQuery: function(btn,e) {
		if (!this.menu.record) {return;}
		MODx.Ajax.request({
			url: mSearch2.config.connector_url
			,params: {
				action: 'mgr/query/remove'
				,query: this.menu.record.query
			}
			,listeners: {
				success: {fn:function(r) {this.refresh();},scope:this}
			}
		});
	}

	,removeQueries: function(btn,e) {
		MODx.msg.confirm({
			title: _('mse2_query_remove_all')
			,text: _('mse2_query_remove_all_confirm')
			,url: mSearch2.config.connector_url
			,params: {
				action: 'mgr/query/remove_all'
			}
			,listeners: {
				success: {fn:function(r) {this.refresh();}, scope:this}
			}
		});
	}

	,Search: function(tf, nv, ov) {
		var s = this.getStore();
		s.baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
	}

	,clearFilter: function(btn,e) {
		var s = this.getStore();
		s.baseParams.query = '';
		Ext.getCmp('mse2-queries-search').setValue('');
		this.getBottomToolbar().changePage(1);
	}

});
Ext.reg('msearch2-grid-queries',mSearch2.grid.Queries);
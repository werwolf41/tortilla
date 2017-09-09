mSearch2.grid.Aliases = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		id: 'msearch2-grid-aliases'
		,url: mSearch2.config.connector_url
		,baseParams: {
			action: 'mgr/alias/getlist'
		}
		,fields: ['id','word','alias','replace']
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,save_action: 'mgr/alias/updatefromgrid'
		,save_callback: this.updateRow
		,autosave: true
		,columns: [
			{header: _('id'), dataIndex: 'id', width: 50, hidden: true}
			,{header: _('mse2_alias_word'), dataIndex: 'word', width: 100, sortable: true, editor: {xtype: 'textfield'}}
			,{header: _('mse2_alias'), dataIndex: 'alias', width: 100, sortable: true, editor: {xtype: 'textfield'}}
			,{header: _('mse2_alias_replace'), dataIndex: 'replace', width: 50, editor: {xtype: 'combo-boolean', renderer: 'boolean'}}
		]
		,tbar: [{
			xtype: 'button'
			,text: '<i class="' + (MODx.modx23 ? 'icon icon-plus' : 'fa fa-plus') + '"></i> ' + _('mse2_alias_create')
			,listeners: {
				click: {fn: this.createAlias, scope:this}
			}
		},'->',{
			xtype: 'textfield'
			,name: 'query'
			,width: 200
			,id: 'mse2-aliases-search'
			,emptyText: _('mse2_alias_search')
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
	mSearch2.grid.Aliases.superclass.constructor.call(this,config);
};
Ext.extend(mSearch2.grid.Aliases,MODx.grid.Grid, {
	windows: {}

	,getMenu: function() {
		var m = [];
		var cls = MODx.modx23 ? 'icon icon-' : 'fa fa-';
		m.push({
			text: '<i class="' + cls + 'edit x-menu-item-icon"></i> ' + _('mse2_alias_update')
			,handler: this.updateAlias
		});
		m.push('-');
		m.push({
			text: '<i class="' + cls + 'trash-o x-menu-item-icon"></i> ' + _('mse2_alias_remove')
			,handler: this.removeAlias
		});
		this.addContextMenuItem(m);
	}

	,createAlias: function(btn,e) {
		if (!this.windows.createAlias) {
			this.windows.createAlias = MODx.load({
				xtype: 'msearch2-window-alias-create'
				,listeners: {
					'success': {fn:function() { this.refresh(); },scope:this}
				}
			});
		}
		this.windows.createAlias.fp.getForm().reset();
		this.windows.createAlias.show(e.target);
	}

	,updateAlias: function(btn,e,row) {
		if (typeof(row) != 'undefined') {this.menu.record = row.data;}

		if (!this.windows.updateAlias) {
			this.windows.updateAlias = MODx.load({
				xtype: 'msearch2-window-alias-update'
				,record: this.menu.record
				,listeners: {
					'success': {fn:function() {this.refresh();},scope:this}
				}
			});
		}
		this.windows.updateAlias.fp.getForm().reset();
		this.windows.updateAlias.fp.getForm().setValues(this.menu.record);
		this.windows.updateAlias.show(e.target);
	}

	,removeAlias: function(btn,e) {
		if (!this.menu.record) {return;}
		MODx.Ajax.request({
			url: mSearch2.config.connector_url
			,params: {
				action: 'mgr/alias/remove'
				,id: this.menu.record.id
			}
			,listeners: {
				success: {fn:function(r) {this.refresh();},scope:this}
			}
		});
	}

	,updateRow: function(response) {
		var row = response.object;
		var items = this.store.data.items;

		for (var i = 0; i < items.length; i++) {
			var item = items[i];
			if (item.id == row.id) {
				item.data = row;
			}
		}
	}

	,Search: function(tf, nv, ov) {
		var s = this.getStore();
		s.baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
	}

	,clearFilter: function(btn,e) {
		var s = this.getStore();
		s.baseParams.query = '';
		Ext.getCmp('mse2-aliases-search').setValue('');
		this.getBottomToolbar().changePage(1);
	}

});
Ext.reg('msearch2-grid-aliases',mSearch2.grid.Aliases);


mSearch2.window.createAlias = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecalias'+Ext.id();
	Ext.applyIf(config,{
		title: _('mse2_alias_create')
		,id: this.ident
		,autoHeight: true
		,width: 475
		,url: mSearch2.config.connector_url
		,action: 'mgr/alias/create'
		,fields: [
			{xtype: 'textfield',fieldLabel: _('mse2_alias_word'),name: 'word',id: 'msearch2-'+this.ident+'-word',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('mse2_alias'),name: 'alias',id: 'msearch2-'+this.ident+'-alias',anchor: '99%'}
			,{xtype: 'xcheckbox',fieldLabel: _('mse2_alias_replace'),name: 'replace',id: 'msearch2-'+this.ident+'-replace'}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	mSearch2.window.createAlias.superclass.constructor.call(this,config);
};
Ext.extend(mSearch2.window.createAlias,MODx.Window);
Ext.reg('msearch2-window-alias-create',mSearch2.window.createAlias);


mSearch2.window.updateAlias = function(config) {
	config = config || {};
	this.ident = config.ident || 'meualias'+Ext.id();
	Ext.applyIf(config,{
		title: _('mse2_alias_update')
		,id: this.ident
		,autoHeight: true
		,width: 475
		,url: mSearch2.config.connector_url
		,action: 'mgr/alias/update'
		,fields: [
			{xtype: 'hidden',fieldLabel: _('id'),name: 'id',id: 'msearch2-'+this.ident+'-id'}
			,{xtype: 'textfield',fieldLabel: _('mse2_alias_word'),name: 'word',id: 'msearch2-'+this.ident+'-word',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('mse2_alias'),name: 'alias',id: 'msearch2-'+this.ident+'-alias',anchor: '99%'}
			,{xtype: 'xcheckbox',fieldLabel: _('mse2_alias_replace'),name: 'replace',id: 'msearch2-'+this.ident+'-replace'}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	mSearch2.window.updateAlias.superclass.constructor.call(this,config);
};
Ext.extend(mSearch2.window.updateAlias,MODx.Window);
Ext.reg('msearch2-window-alias-update',mSearch2.window.updateAlias);
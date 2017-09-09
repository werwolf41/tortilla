mSearch2.grid.Dictionaries = function(config) {
	config = config || {};

	Ext.applyIf(config,{
		id: 'msearch2-grid-dictionaries'
		,url: mSearch2.config.connector_url
		,baseParams: {
			action: 'mgr/dictionary/getlist'
		}
		,fields: ['dictionary','language','installed']
		,autoHeight: true
		,paging: false
		,columns: [
			{header: _('mse2_dictionary'), dataIndex: 'dictionary', width: 50}
			,{header: _('mse2_language'), dataIndex: 'language', width: 100}
			,{header: _('mse2_dictionary_installed'), dataIndex: 'installed', width: 100, renderer: this.renderBoolean}
		]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				if (!row.data.installed) {
					this.installDictionary(grid, e, row);
				}
			}
		}
	});
	mSearch2.grid.Dictionaries.superclass.constructor.call(this,config);
};
Ext.extend(mSearch2.grid.Dictionaries,MODx.grid.Grid, {
	windows: {}

	,getMenu: function(grid,index) {
		var m = [];
		var record = grid.getStore().data.items[index].data;

		var cls = MODx.modx23 ? 'icon icon-' : 'fa fa-';
		if (record.installed) {
			m.push({
				text: '<i class="' + cls + 'trash-o x-menu-item-icon"></i> ' + _('mse2_dictionary_remove')
				,handler: this.removeDictionary
			});
		}
		else {
			m.push({
				text: '<i class="' + cls + 'download x-menu-item-icon"></i> ' + _('mse2_dictionary_install')
				,handler: this.installDictionary
			});
		}

		this.addContextMenuItem(m);
	}

	,renderBoolean: function(value) {
		return value
			? '<span style="color:green;">' + _('yes') + '</span>'
			: '<span style="color:red;">' + _('no') + '</span>'
	}

	,installDictionary: function(btn,e,row) {
		if (typeof(row) != 'undefined') {this.menu.record = row.data;}

		if (!this.windows.installDictionary) {
			this.windows.installDictionary = MODx.load({
				xtype: 'msearch2-window-dictionary-update'
				,record: this.menu.record
				,listeners: {
					'success': {fn:function() {this.refresh();},scope:this}
				}
			});
		}
		this.windows.installDictionary.fp.getForm().reset();
		this.windows.installDictionary.fp.getForm().setValues(this.menu.record);
		this.windows.installDictionary.show(e.target);
	}

	,removeDictionary: function(btn,e) {
		if (!this.menu.record) {return;}
		MODx.Ajax.request({
			url: mSearch2.config.connector_url
			,params: {
				action: 'mgr/dictionary/remove'
				,dictionary: this.menu.record.dictionary
			}
			,listeners: {
				success: {fn:function(r) {this.refresh();},scope:this}
			}
		});
	}

});
Ext.reg('msearch2-grid-dictionaries',mSearch2.grid.Dictionaries);


mSearch2.window.installDictionary = function(config) {
	config = config || {};
	this.ident = config.ident || 'meudictionary'+Ext.id();
	Ext.applyIf(config,{
		title: _('mse2_dictionary_install')
		,id: this.ident
		,height: 200
		,width: 475
		,url: mSearch2.config.connector_url
		,action: 'mgr/dictionary/install'
		,fields: [
			{xtype: 'hidden',name: 'dictionary',id: 'msearch2-'+this.ident+'-dictionary'}
			,{xtype: 'msearch2-combo-mirror',fieldLabel: _('mse2_dictionary_mirror'),name: 'mirror',id: 'msearch2-'+this.ident+'-mirror',anchor: '99%'}
		]
		//,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	mSearch2.window.installDictionary.superclass.constructor.call(this,config);
};
Ext.extend(mSearch2.window.installDictionary,MODx.Window);
Ext.reg('msearch2-window-dictionary-update',mSearch2.window.installDictionary);


// Комбобоксы статусов, складов и категорий товаров
mSearch2.combo.mirror = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'mirror'
		,hiddenName: 'mirror'
		,displayField: 'name'
		,valueField: 'mirror'
		,fields: ['name','location','mirror']
		,pageSize: 20
		,emptyText: _('mse2_dictionary_mirror_select')
		,url: mSearch2.config.connector_url
		,baseParams: {
			action: 'mgr/dictionary/mirrors'
		}
		,tpl: new Ext.XTemplate(''
			+'<tpl for="."><div class="msearch2-mirrors-item">'
				+'{name} <small>{location}</small>'
			+'</div></tpl>',{
			compiled: true
		})
		,itemSelector: 'div.msearch2-mirrors-item'
	});
	mSearch2.combo.mirror.superclass.constructor.call(this,config);
};
Ext.extend(mSearch2.combo.mirror,MODx.combo.ComboBox);
Ext.reg('msearch2-combo-mirror',mSearch2.combo.mirror);
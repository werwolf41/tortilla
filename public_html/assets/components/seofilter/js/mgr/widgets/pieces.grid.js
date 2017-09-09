seoFilter.grid.Pieces = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'seofilter-grid-pieces';
	}
	Ext.applyIf(config, {
		url: seoFilter.config.connector_url,
		fields: this.getFields(config),
		columns: this.getColumns(config),
		tbar: this.getTopBar(config),
		sm: new Ext.grid.CheckboxSelectionModel(),
		baseParams: {
			action: 'mgr/piece/getlist'
		},
		listeners: {
			rowDblClick: function (grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updatePiece(grid, e, row);
			}
		},
		viewConfig: {
			forceFit: true,
			enableRowBody: true,
			autoFill: true,
			showPreview: true,
			scrollOffset: 0,
			getRowClass: function (rec, ri, p) {
                var result = [];
                if(!rec.data.alias) { result.push('seofilter-grid-row-disabled'); }
                if(rec.data.use_count == '0') { result.push('seofilter-grid-row-not-used'); }

				return  result.join(' ');
			}
		},
		paging: true,
		remoteSort: true,
		autoHeight: true
	});
    seoFilter.grid.Pieces.superclass.constructor.call(this, config);

	// Clear selection on grid refresh
	this.store.on('load', function () {
		if (this._getSelectedIds().length) {
			this.getSelectionModel().clearSelections();
		}
	}, this);
};
Ext.extend(seoFilter.grid.Pieces, MODx.grid.Grid, {
	windows: {},

	getMenu: function (grid, rowIndex) {
		var ids = this._getSelectedIds();

		var row = grid.getStore().getAt(rowIndex);
		var menu = seoFilter.utils.getMenu(row.data['actions'], this, ids);

		this.addContextMenuItem(menu);
	},

	createPiece: function (btn, e) {
		var w = MODx.load({
			xtype: 'seofilter-piece-window-create',
			id: Ext.id(),
			listeners: {
				success: {
					fn: function () {
						this.refresh();
					}, scope: this
				}
			}
		});
        var defaultValues = { };
        var gridParamFilter = this.getStore().baseParams.filter;
        if(typeof gridParamFilter != 'undefined' && gridParamFilter != '') {
            defaultValues.param = gridParamFilter;
        }
        w.reset();
		w.setValues(defaultValues);
		w.show(e.target);
	},

    generatePieces: function (btn, e) {
        var gridParamFilter = this.getStore().baseParams.filter;
        if(typeof gridParamFilter == 'undefined' || gridParamFilter == '') {
            MODx.msg.alert(_('seofilter_pieces_generate'), _('seofilter_pieces_generate_err_no_filter'));
            return false;
        }

        MODx.msg.confirm({
            title: _('seofilter_pieces_generate'),
            text: _('seofilter_pieces_generate_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/piece/generate',
                param: gridParamFilter
            },
            listeners: {
                success: {
                    fn: function (r) {
                        this.refresh();
                    }, scope: this
                }
            }
        });
    },

	updatePiece: function (btn, e, row) {
		if (typeof(row) != 'undefined') {
			this.menu.record = row.data;
		}
		else if (!this.menu.record) {
			return false;
		}
		var id = this.menu.record.id;

		MODx.Ajax.request({
			url: this.config.url,
			params: {
				action: 'mgr/piece/get',
				id: id
			},
			listeners: {
				success: {
					fn: function (r) {
						var w = MODx.load({
							xtype: 'seofilter-piece-window-update',
							id: Ext.id(),
							record: r,
							listeners: {
								success: {
									fn: function () {
										this.refresh();
									}, scope: this
								}
							}
						});
						w.reset();
						w.setValues(r.object);
						w.show(e.target);
					}, scope: this
				}
			}
		});
	},

	removePiece: function (act, btn, e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.msg.confirm({
			title: ids.length > 1
				? _('seofilter_pieces_remove')
				: _('seofilter_piece_remove'),
			text: ids.length > 1
				? _('seofilter_pieces_remove_confirm')
				: _('seofilter_piece_remove_confirm'),
			url: this.config.url,
			params: {
				action: 'mgr/piece/remove',
				ids: Ext.util.JSON.encode(ids)
			},
			listeners: {
				success: {
					fn: function (r) {
						this.refresh();
					}, scope: this
				}
			}
		});
		return true;
	},

	getFields: function (config) {
		return ['id', 'param_name', 'value', 'alias', 'correction', 'use_count', 'actions'];
	},

	getColumns: function (config) {
		return [/*{
			header: _('seofilter_piece_id'),
			dataIndex: 'id',
			sortable: true,
			width: 50
		},*/ {
			header: _('seofilter_piece_param'),
			dataIndex: 'param_name',
			sortable: true,
			width: 150
		}, {
            header: _('seofilter_piece_value'),
            dataIndex: 'value',
            sortable: true,
            width: 150
        }, {
            header: _('seofilter_piece_alias'),
            dataIndex: 'alias',
            sortable: true,
            width: 150
        }, {
            header: _('seofilter_piece_correction'),
            dataIndex: 'correction',
            sortable: true,
            width: 150
        }, {
            header: _('seofilter_piece_use_count'),
            dataIndex: 'use_count',
            sortable: false,
            width: 150
        }, {
			header: _('seofilter_grid_actions'),
			dataIndex: 'actions',
			renderer: seoFilter.utils.renderActions,
			sortable: false,
			width: 80,
			id: 'actions'
		}];
	},

	getTopBar: function (config) {
		return [{
			text: '<i class="icon icon-plus"></i>&nbsp;' + _('seofilter_piece_create'),
			handler: this.createPiece,
			scope: this
		},{
            text: '<i class="icon icon-cubes"></i>&nbsp;' + _('seofilter_pieces_generate'),
            handler: this.generatePieces,
            scope: this
        },
            '->',
        {
            xtype: 'seofilter-combo-param',
            name: 'filter',
            width: 200,
            id: config.id + '-filter-field',
            emptyText: _('seofilter_grid_filter'),
            listeners: {
                select: {
                    fn: function (tf) { this._doFilter(tf); },
                    scope: this
                }
            }
        }, {
			xtype: 'textfield',
			name: 'query',
			width: 200,
			id: config.id + '-search-field',
			emptyText: _('seofilter_grid_search'),
			listeners: {
				render: {
					fn: function (tf) {
						tf.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
							this._doSearch(tf);
						}, this);
					}, scope: this
				}
			}
		}, {
			xtype: 'button',
			id: config.id + '-search-clear',
			text: '<i class="icon icon-times"></i>',
			listeners: {
				click: {fn: this._clearSearch, scope: this}
			}
		}];
	},

	onClick: function (e) {
		var elem = e.getTarget();
		if (elem.nodeName == 'BUTTON') {
			var row = this.getSelectionModel().getSelected();
			if (typeof(row) != 'undefined') {
				var action = elem.getAttribute('action');
				if (action == 'showMenu') {
					var ri = this.getStore().find('id', row.id);
					return this._showMenu(this, ri, e);
				}
				else if (typeof this[action] === 'function') {
					this.menu.record = row.data;
					return this[action](this, e);
				}
			}
		}
		return this.processEvent('click', e);
	},

	_getSelectedIds: function () {
		var ids = [];
		var selected = this.getSelectionModel().getSelections();

		for (var i in selected) {
			if (!selected.hasOwnProperty(i)) {
				continue;
			}
			ids.push(selected[i]['id']);
		}

		return ids;
	},

    _doFilter: function (tf, nv, ov) {
        this.getStore().baseParams.filter = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
	_doSearch: function (tf, nv, ov) {
		this.getStore().baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	},

	_clearSearch: function (btn, e) {
		this.getStore().baseParams.filter = '';
        this.getStore().baseParams.query = '';
		Ext.getCmp(this.config.id + '-filter-field').setValue('');
        Ext.getCmp(this.config.id + '-search-field').setValue('');
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
});
Ext.reg('seofilter-grid-pieces', seoFilter.grid.Pieces);

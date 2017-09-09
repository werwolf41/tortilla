easyComm.grid.Messages = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'ec-grid-messages';
    }
    config.record = config.record || {};
    config.record.id = config.record.id || 0;
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config, {
        url: easyComm.config.connector_url,
        fields: easyComm.config.message_fields,
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: this.sm,
        baseParams: {
            action: 'mgr/message/getlist',
            resource_id: config.record.id,
            thread_id: config.thread
        },
        listeners: {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateMessage(grid, e, row);
            }
        },
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
            getRowClass: function (rec, ri, p) {
                if(rec.data.deleted) {
                    return 'ec-grid-row-deleted';
                }
                return !rec.data.published ? 'ec-grid-row-disabled' : '';
            }
        },
        paging: true,
        remoteSort: true,
        autoHeight: true
    });
    easyComm.grid.Messages.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(easyComm.grid.Messages, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);

        var m = [];
        if (ids.length > 1) {
            m.push({text: '<i class="x-menu-item-icon icon icon-power-off"></i>'+_('ec_messages_publish') ,handler: this.publishMessage});
            m.push({text: '<i class="x-menu-item-icon icon icon-power-off"></i>'+_('ec_messages_unpublish') ,handler: this.unpublishMessage});
            m.push('-');
            m.push({ text: '<i class="x-menu-item-icon icon icon-trash-o"></i>'+_('ec_messages_delete'), handler: this.deleteMessage });
            m.push({ text: '<i class="x-menu-item-icon icon icon-undo"></i>'+_('ec_messages_undelete'), handler: this.undeleteMessage });
            m.push('-');
            m.push({text: '<i class="x-menu-item-icon icon icon-remove"></i>'+_('ec_messages_remove'),handler: this.removeMessage});
        } else {
            m.push({text: '<i class="x-menu-item-icon icon icon-edit"></i>'+_('ec_message_update'),handler: this.updateMessage});
            m.push({
                text: row.data.published ? '<i class="x-menu-item-icon icon icon-power-off"></i>'+_('ec_message_unpublish') : '<i class="x-menu-item-icon icon icon-power-off action-green"></i>'+_('ec_message_publish'),
                handler: row.data.published ? this.unpublishMessage : this.publishMessage
            });
            if(row.data.published) {
                m.push('-');
                m.push({text: '<i class="x-menu-item-icon icon icon-eye"></i>'+_('ec_message_view_on_site'),handler: this.viewMessage});
            }
            m.push('-');
            m.push({
                text: row.data.deleted ? '<i class="x-menu-item-icon icon icon-undo"></i>'+_('ec_message_undelete') : '<i class="x-menu-item-icon icon icon-trash-o"></i>'+_('ec_message_delete'),
                handler: row.data.deleted ? this.undeleteMessage : this.deleteMessage
            });
            m.push({text: '<i class="x-menu-item-icon icon icon-remove"></i>'+_('ec_message_remove'),handler: this.removeMessage});
        }

        this.addContextMenuItem(m);
    },

    createMessage: function (btn, e) {
        var w = MODx.load({
            xtype: 'ec-message-window-create',
            id: Ext.id(),
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        w.reset();
        w.setValues({
            published: true,
            reply_author: easyComm.config.default_reply_author,
            thread: easyComm.config.default_thread,
            rating: easyComm.config.default_rating
        });
        w.show(e.target);
    },

    updateMessage: function (btn, e, row) {
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
                action: 'mgr/message/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'ec-message-window-update',
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
                        if(!r.object.reply_author && easyComm.config.default_reply_author) {
                            w.setValues({
                                reply_author: easyComm.config.default_reply_author
                            });
                        }
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },

    // some action with one or multiple messages
    messageAction: function(actionMethod) {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                //action: 'mgr/message/' + actionMethod,
                action: 'mgr/message/multiple',
                actionMethod: actionMethod,
                ids: Ext.util.JSON.encode(ids)
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                },
                failure: {
                    fn: function (response) {
                        MODx.msg.alert(_('error'), response.message);
                    }, scope: this
                }
            }
        })
    },

    publishMessage: function (act, btn, e) {
        this.messageAction('publish');
    },
    unpublishMessage: function (act, btn, e) {
        this.messageAction('unpublish');
    },

    deleteMessage: function (act, btn, e) {
        this.messageAction('delete');
    },
    undeleteMessage: function (act, btn, e) {
        this.messageAction('undelete');
    },

    viewMessage: function(act, btn, e) {
        window.open(this.menu.record['preview_url'] + '#ec-' + this.menu.record['thread_name'] + '-message-' + this.menu.record['id']);
        return false;
    },

    removeMessage: function (act, btn, e) {
        var ids = this._getSelectedIds();
        Ext.MessageBox.confirm(
            ids.length > 1 ? _('ec_messages_remove') : _('ec_message_remove'),
            ids.length > 1 ? _('ec_messages_remove_confirm') : _('ec_message_remove_confirm'),
            function (val) {
                if (val == 'yes') {
                    this.messageAction('remove');
                }
            },
            this
        );
    },

    getColumns: function (config) {
        var columns = {
            id: { sortable: true, width: 70 },
            thread: { sortable: true, width: 100 },
            thread_name: { sortable: true, width: 100 },
            thread_resource: { sortable: true, width: 100 },
            thread_title: { sortable: true, width: 100 },
            resource_pagetitle: { sortable: true, width: 100 },
            subject: { sortable: true, width: 150 },
            date: { sortable: true, width: 100 },
            user_name: { sortable: true, width: 100 },
            user_email: { sortable: true, width: 100 },
            user_contacts: { sortable: true, width: 100 },
            rating: { sortable: true, width: 90, renderer: easyComm.utils.renderRating},
            text: { sortable: true, width: 200 },
            reply_author: { sortable: true, width: 100 },
            reply_text: { sortable: true, width: 200 },
            ip: { sortable: true, width: 100 }
        };

        for (i in easyComm.plugin) {
            if (typeof(easyComm.plugin[i]['getColumns']) == 'function') {
                var pluginColumns = easyComm.plugin[i].getColumns();
                Ext.apply(columns, pluginColumns);
            }
        }

        var fields = [this.sm];
        for (var i = 0; i < easyComm.config.message_grid_fields.length; i++) {
            var field = easyComm.config.message_grid_fields[i];
            if (columns[field]) {
                Ext.applyIf(columns[field], {
                    header: _('ec_message_' + field)
                    ,dataIndex: field
                });
                fields.push(columns[field]);
            }
        }
        return fields;
    },

    getTopBar: function (config) {
        return [{
            text: '<i class="icon icon-plus"></i> ' + _('ec_message_create'),
            handler: this.createMessage,
            scope: this
        }, '->', {
            xtype: 'textfield',
            name: 'query',
            width: 200,
            id: config.id + '-search-field',
            emptyText: _('ec_grid_search'),
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

    _doSearch: function (tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },

    _clearSearch: function (btn, e) {
        this.getStore().baseParams.query = '';
        Ext.getCmp(this.config.id + '-search-field').setValue('');
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
});
Ext.reg('ec-grid-messages', easyComm.grid.Messages);
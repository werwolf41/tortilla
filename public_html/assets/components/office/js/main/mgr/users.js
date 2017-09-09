Ext.ComponentMgr.onAvailable('modx-panel-users', function () {
    Ext.override(MODx.grid.User, {
        getMenu: MODx.grid.User.prototype.getMenu.createSequence(function (grid, rowIndex) {
            var data = grid.getStore().getAt(rowIndex).data;
            if (!data.active || data.blocked) {
                return;
            }
            this.addContextMenuItem(['-', {
                text: _('office_auth_as_user'),
                handler: function () {
                    window.open(MODx.config.site_url + '?action=office/login_as&user_id=' + data.id)
                }, scope: this
            }])
        })
    })
});
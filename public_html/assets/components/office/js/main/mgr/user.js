Ext.ComponentMgr.onAvailable('modx-action-buttons', function () {
    this.items.unshift({
        text: _('office_auth_as_user'),
        id: 'office_auth_as_user',
        handler: function () {
            window.open(MODx.config.site_url + '?action=office/login_as&user_id=' + MODx.activePage.user)
        },
    });
});

Ext.ComponentMgr.onAvailable('modx-panel-user', function () {
    this.on('ready', function (data) {
        var btn = Ext.getCmp('office_auth_as_user');
        if (btn && (!data.active || data.blocked)) {
            btn.disable();
        } else if (btn) {
            btn.enable();
        }
    });
});

Ext.override(MODx.panel.User, {
    success: MODx.panel.User.prototype.success.createSequence(function (o) {
        var data = o.result.object;
        var btn = Ext.getCmp('office_auth_as_user');
        if (btn && (!data.active || data.blocked)) {
            btn.disable();
        } else if (btn) {
            btn.enable();
        }
    }),
});
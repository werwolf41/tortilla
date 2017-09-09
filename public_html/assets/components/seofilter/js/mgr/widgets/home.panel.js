seoFilter.panel.Home = function (config) {
	config = config || {};
	Ext.apply(config, {
		baseCls: 'modx-formpanel',
		layout: 'anchor',
		/*
		 stateful: true,
		 stateId: 'seofilter-panel-home',
		 stateEvents: ['tabchange'],
		 getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
		 */
		hideMode: 'offsets',
		items: [{
			html: '<h2>' + _('seofilter') + '</h2>',
			cls: '',
			style: { margin: '15px 0' }
		}, {
			xtype: 'modx-tabs',
			defaults: { border: false, autoHeight: true },
			border: true,
			hideMode: 'offsets',
			items: [{
				title: _('seofilter_params'),
				layout: 'anchor',
				items: [{
					html: _('seofilter_intro_msg'),
					cls: 'panel-desc'
				}, {
					xtype: 'seofilter-grid-params',
					cls: 'main-wrapper'
				}]
			}, {
                title: _('seofilter_pieces'),
                layout: 'anchor',
                items: [{
                    html: _('seofilter_intro_msg'),
                    cls: 'panel-desc'
                }, {
                    xtype: 'seofilter-grid-pieces',
                    cls: 'main-wrapper'
                }]
            }, {
                title: _('seofilter_pieces_content'),
                layout: 'anchor',
                items: [{
                    html: _('seofilter_intro_msg'),
                    cls: 'panel-desc'
                }, {
                    xtype: 'seofilter-grid-pieces-content',
                    cls: 'main-wrapper'
                }]
            }, {
                title: _('seofilter_default_content'),
                layout: 'anchor',
                items: [{
                    html: _('seofilter_intro_msg'),
                    cls: 'panel-desc'
                }, {
                    xtype: 'seofilter-grid-default-content',
                    cls: 'main-wrapper'
                }]
            }]
		}]
	});
    seoFilter.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(seoFilter.panel.Home, MODx.Panel);
Ext.reg('seofilter-panel-home', seoFilter.panel.Home);

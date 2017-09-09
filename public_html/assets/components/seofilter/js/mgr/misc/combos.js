seoFilter.combo.ParamType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.ArrayStore({
            id: 0,
            fields: ['code', 'display'],
            data: [
                ['field', _('seofilter_param_type_field')],
                ['field_json', _('seofilter_param_type_field_json')],
                ['vendor', _('seofilter_param_type_vendor')]
            ]
        }),
        mode: 'local',
        displayField: 'display',
        valueField: 'code'
    });
    seoFilter.combo.ParamType.superclass.constructor.call(this,config);
};
Ext.extend(seoFilter.combo.ParamType,MODx.combo.ComboBox);
Ext.reg('seofilter-combo-param-type', seoFilter.combo.ParamType);


seoFilter.combo.Param = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        fields: ['id','name']
        ,valueField: 'id'
        ,displayField: 'name'
        ,hiddenName: 'thread'
        ,allowBlank: true
        ,url: seoFilter.config.connector_url
        ,baseParams: {
            action: 'mgr/param/getcombolist'
            ,combo: 1
            ,id: config.value
        }
        ,pageSize: 20
        ,width: 300
        ,editable: true
    });
    seoFilter.combo.Param.superclass.constructor.call(this,config);
};
Ext.extend(seoFilter.combo.Param, MODx.combo.ComboBox);
Ext.reg('seofilter-combo-param',seoFilter.combo.Param);


seoFilter.combo.Category = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'seofilter-combo-category'
        ,fieldLabel: _('ms2_link')
        ,description: ''
        ,fields: ['id','pagetitle','parents']
        ,valueField: 'id'
        ,displayField: 'pagetitle'
        ,name: 'parent-cmb'
        ,hiddenName: 'parent-cmp'
        ,allowBlank: true
        ,url: seoFilter.config.connector_url
        ,baseParams: {
            action: 'mgr/resource/getcategories'
            ,combo: 1
            ,id: config.value
            //,limit: 0
        }
        ,tpl: new Ext.XTemplate(''
            +'<tpl for="."><div class="x-combo-list-item seofilter-category-list-item">'
            +'<tpl if="parents">'
            +'<span class="parents">'
            +'<tpl for="parents">'
            +'<nobr><small>{pagetitle} / </small></nobr>'
            +'</tpl>'
            +'</span>'
            +'</tpl>'
            +'<span><b>{pagetitle}</b> <small>({id})</small></span>'
            +'</div></tpl>',{
            compiled: true
        })
        ,itemSelector: 'div.seofilter-category-list-item'
        ,pageSize: 20
        //,typeAhead: true
        ,editable: true
    });
    seoFilter.combo.Category.superclass.constructor.call(this,config);
};
Ext.extend(seoFilter.combo.Category, MODx.combo.ComboBox);
Ext.reg('seofilter-combo-category',seoFilter.combo.Category);
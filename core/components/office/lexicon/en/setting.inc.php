<?php

$_lang['area_office_main'] = 'Main';
$_lang['area_office_auth'] = 'Authorization';
$_lang['area_office_profile'] = 'Profile';
$_lang['area_office_ms2'] = 'miniShop2';

$_lang['setting_office_frontend_css'] = 'Office main styles';
$_lang['setting_office_frontend_css_desc'] = 'Path to file with the main styles of the office. If you want to use your own styles - specify them here, or clean this parameter and load them in site template.';
$_lang['setting_office_extjs_css'] = 'ExtJS custom css';
$_lang['setting_office_extjs_css_desc'] = 'You can specify path to custom css for styling office when ExtJS used in the controller.';
$_lang['setting_office_frontend_js'] = 'Office main script';
$_lang['setting_office_frontend_js_desc'] = 'Path to file with the main javascript of the office. If you want to use your own scripts - specify them here, or clean this parameter and load them in site template.';
$_lang['setting_office_sanitize_pcre'] = 'Data cleansing';
$_lang['setting_office_sanitize_pcre_desc'] = 'Regular expression that specifies what will be cut when saving profile data.';
$_lang['setting_office_controllers_paths'] = 'Controllers paths';
$_lang['setting_office_controllers_paths_desc'] = 'JSON encoded string with array where keys are names of controllers and values is path where to find them. For example: {"extra":"[[++core_path]]components/extra/controllers/office/"}. Office will try to load "extra/someaction" from this path in the first place.';
$_lang['setting_office_auth_frontend_css'] = 'Styles of controller Auth';
$_lang['setting_office_auth_frontend_css_desc'] = 'Path to file with Auth styles. If you want to use your own styles - specify them here, or clean this parameter and load them in site template.';
$_lang['setting_office_auth_frontend_js'] = 'Script of controller Auth';
$_lang['setting_office_auth_frontend_js_desc'] = 'Path to file with the Auth javascript. If you want to use your own scripts - specify them here, or clean this parameter and load them in site template.';
$_lang['setting_office_profile_frontend_css'] = 'Styles of controller Profile';
$_lang['setting_office_profile_frontend_css_desc'] = 'Path to file with Profile styles. If you want to use your own styles - specify them here, or clean this parameter and load them in site template.';
$_lang['setting_office_profile_frontend_js'] = 'Script of controller Profile';
$_lang['setting_office_profile_frontend_js_desc'] = 'Path to file with the Profile javascript. If you want to use your own scripts - specify them here, or clean this parameter and load them in site template.';
$_lang['setting_office_profile_force_email_as_username'] = 'Email as username';
$_lang['setting_office_profile_force_email_as_username_desc'] = 'This option will copy the email address of the user in the username each time the page is loaded, so they were always the same.';
$_lang['setting_office_ms2_frontend_css'] = 'Styles of controller miniShop2';
$_lang['setting_office_ms2_frontend_css_desc'] = 'Path to file with miniShop2 styles. If you want to use your own styles - specify them here, or clean this parameter and load them in site template.';
$_lang['setting_office_ms2_frontend_js'] = 'Script of controller miniShop2';
$_lang['setting_office_ms2_frontend_js_desc'] = 'Path to file with the miniShop2 javascript. If you want to use your own scripts - specify them here, or clean this parameter and load them in site template.';

$_lang['setting_office_sms_provider'] = 'SMS Provider';
$_lang['setting_office_sms_provider_desc'] = 'Providers available by default: SmsRu and ByteHand';
$_lang['setting_office_sms_id'] = 'Client login';
$_lang['setting_office_sms_id_desc'] = 'Unique login for authorization at the provider. For SmsRu it is "api_id", for ByteHand it is just "id".';
$_lang['setting_office_sms_key'] = 'Client key';
$_lang['setting_office_sms_key_desc'] = 'Key for authorization at the provider. By default required only for ByteHand.';
$_lang['setting_office_sms_from'] = 'Sender';
$_lang['setting_office_sms_from_desc'] = 'The name of SMS sender. Usually <strong>you need to confirm it</strong> at provider before use.';

$_lang['setting_office_auth_mode'] = 'Auth mode';
$_lang['setting_office_auth_mode_desc'] = 'You can set the mode of authentication: email or phone.';
$_lang['setting_office_auth_page_id'] = 'Auth page id';
$_lang['setting_office_auth_page_id_desc'] = 'Id of the site page, where controller Auth is called. This setting is automatically filled in when you call the controller first time.';
$_lang['setting_office_auth_page_id'] = 'Profile page id';
$_lang['setting_office_auth_page_id_desc'] = 'Id of the site page, where controller Profile is called. This setting is automatically filled in when you call the controller first time.';
$_lang['setting_office_profile_required_fields'] = 'Required fields of profile';
$_lang['setting_office_profile_required_fields_desc'] = 'Specify required user profile fields. The user will constantly go to edit profile, while not fill in these fields.';

$_lang['setting_office_ms2_date_format'] = 'date Format';
$_lang['setting_office_ms2_date_format_desc'] = 'Specify the format of date, using the syntax of php function strftime(). For example, "%d.%m.%y %H:%M".';
$_lang['setting_office_ms2_order_grid_fields'] = 'Fields of the orders table';
$_lang['setting_office_ms2_order_grid_fields_desc'] = 'Comma separated list of fields in the table of orders. Available: "createdon,updatedon,num,cost,cart_cost,delivery_cost,weight,status,delivery,payment,customer,receiver".';
$_lang['setting_office_ms2_order_form_fields'] = 'Main fields of order form';
$_lang['setting_office_ms2_order_form_fields_desc'] = 'Comma separated list of the main fields in the order, which will be shown at the first tab. Available: "weight,createdon,updatedon,cart_cost,delivery_cost,status,delivery,payment".';
$_lang['setting_office_ms2_order_address_fields'] = 'Fields of order address';
$_lang['setting_office_ms2_order_address_fields_desc'] = 'Comma separated list of address of order, which will be shown on the third tab. Available: "receiver,phone,index,country,region,metro,building,city,street,room". If empty, this tab will be hidden.';
$_lang['setting_office_ms2_order_product_fields'] = 'Field of the purchased products';
$_lang['setting_office_ms2_order_product_fields_desc'] = 'which will be shown list of ordered products. Available: "count,price,weight,cost,options". Product fields specified with the prefix "product_", for example "product_pagetitle,product_article". Additionaly, you can specify a values from the options field with the prefix "option_", for example: "option_color,option_size".';
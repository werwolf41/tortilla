<?php

$_lang['area_office_main'] = 'Hauptmenu';
$_lang['area_office_auth'] = 'Autorisation';
$_lang['area_office_profile'] = 'Profil';
$_lang['area_office_ms2'] = 'miniShop2';

$_lang['setting_office_frontend_css'] = 'Office Main Styles';
$_lang['setting_office_frontend_css_desc'] = 'Pfad zur Datei mit main styles von Office. Falls Du Deine eigenen Stile verwenden willst, spezifiziere sie hier oder l&ouml;sche diesen Eintrag und lade die Stile im Template.';
$_lang['setting_office_extjs_css'] = 'ExtJS: eigenes CSS';
$_lang['setting_office_extjs_css_desc'] = 'Pfad zu eigenem CSS, um Office zu stylen f&uuml;r ExtJS im Controller.';
$_lang['setting_office_frontend_js'] = 'Office Hauptscript';
$_lang['setting_office_frontend_js_desc'] = 'Pfad zum Haupt-Javascript von Office. F&uuml;r ein eigenes Script hier den Pfad angeben oder den Eintrag l&ouml;schen und im Template laden.';
$_lang['setting_office_sanitize_pcre'] = 'Daten Bereinigung';
$_lang['setting_office_sanitize_pcre_desc'] = 'Regul&aauml;rer Ausdruck der ausgef&uuml;hrt wird, wenn ein Benutzerprofil gespeichert wird.';
$_lang['setting_office_controllers_paths'] = 'Controller Pfade';
$_lang['setting_office_controllers_paths_desc'] = 'JSON String mit Array, in dem die Keys die Namen der Controller sind und die Values der Pfad, wo diese zu finden sind. Zum Beispiel: {"extra":"[[++core_path]]components/extra/controllers/office/"}. Office wird versuchen diese als erste von diesem Pfad zu laden "extra/someaction".';
$_lang['setting_office_auth_frontend_css'] = 'Styles der Controller Auth';
$_lang['setting_office_auth_frontend_css_desc'] = 'Pfad zur Datei mit Auth Styles.Falls Du Deine eigenen Stile verwenden willst, spezifiziere sie hier oder l&ouml;sche diesen Eintrag und lade die Stile im Template.';
$_lang['setting_office_auth_frontend_js'] = 'Script von Controller Auth';
$_lang['setting_office_auth_frontend_js_desc'] = 'Pfad zur Datei mit Auth javascript. Falls Du Deine eigenen Scripts verwenden willst, spezifiziere sie hier oder l&ouml;sche diesen Eintrag und lade die Stile im Template.';
$_lang['setting_office_profile_frontend_css'] = 'Styles von Controller Profile';
$_lang['setting_office_profile_frontend_css_desc'] = 'Pfad zur Datei mit Profile Styles. Falls Du Deine eigenen Stile verwenden willst, spezifiziere sie hier oder l&ouml;sche diesen Eintrag und lade die Stile im Template.';
$_lang['setting_office_profile_frontend_js'] = 'Script von Controller Profile';
$_lang['setting_office_profile_frontend_js_desc'] = 'Pfad zur Datei mit Profile javascript. Falls Du Deine eigenen Scripts verwenden willst, spezifiziere sie hier oder l&ouml;sche diesen Eintrag und lade die Stile im Template.';
$_lang['setting_office_profile_force_email_as_username'] = 'Email als Benutzername';
$_lang['setting_office_profile_force_email_as_username_desc'] = 'Diese Option kopiert die Emailadresse der Benutzers in den Benutzernamen, jedes mal wenn die Seite geladen wird. So sind sie immer gleich.';
$_lang['setting_office_ms2_frontend_css'] = 'Styles von Controller miniShop2';
$_lang['setting_office_ms2_frontend_css_desc'] = 'Pfad zur Datei mit miniShop2 Styles. Falls Du Deine eigenen Stile verwenden willst, spezifiziere sie hier oder l&ouml;sche diesen Eintrag und lade die Stile im Template.';
$_lang['setting_office_ms2_frontend_js'] = 'Script von Controller miniShop2';
$_lang['setting_office_ms2_frontend_js_desc'] = 'Pfad zur Datei mit miniShop2 javascript. Falls Du Deine eigenen Scripts verwenden willst, spezifiziere sie hier oder l&ouml;sche diesen Eintrag und lade die Stile im Template.';

$_lang['setting_office_sms_provider'] = 'SMS Provider';
$_lang['setting_office_sms_provider_desc'] = 'Vorhandene, eingebaute Providers : SmsRu and ByteHand';
$_lang['setting_office_sms_id'] = 'User Login';
$_lang['setting_office_sms_id_desc'] = 'Eindeutiges Login f&uuml;r Autorisierung beim Provider. F&uuml;r SmsRu ist es "api_id", f&uuml;r ByteHand ist es einfach "id".';
$_lang['setting_office_sms_key'] = 'Client Key';
$_lang['setting_office_sms_key_desc'] = 'Schl&uuml;ssel f&uuml;r Autorisierung beim Provider. Nur f&uuml;r ByteHand notwendig.';
$_lang['setting_office_sms_from'] = 'Absender';
$_lang['setting_office_sms_from_desc'] = 'Name des SMS Absenders. Normalerweise muss man diesen zuer st beim Provider best&aauml;tigen, bevor er benutzt werden kann.';

$_lang['setting_office_auth_mode'] = 'Authentifizierung Modus';
$_lang['setting_office_auth_mode_desc'] = 'Modus der Authentifizierung: email oder phone.';
$_lang['setting_office_auth_page_id'] = 'Auth Page id';
$_lang['setting_office_auth_page_id_desc'] = 'ID der Page, wo der Controller Auth aufgerufen wird. Dieser Wert wird automatisch beim ersten Aufruf des Controllers hier eingetragen.';
$_lang['setting_office_auth_page_id'] = 'Profil Page id';
$_lang['setting_office_auth_page_id_desc'] = 'ID der Page, wo der Controller Profile aufgerufen wird. Dieser Wert wird automatisch beim ersten Aufruf des Controllers hier eingetragen.';
$_lang['setting_office_profile_required_fields'] = 'Erfoderliche Felder f&uuml;r Profil';
$_lang['setting_office_profile_required_fields_desc'] = 'Spezifiziere die erforderlichen Benutzerfelder im Profil. Der Benutzer wird auf die Profilseite umgeleitet, wenn diese Felder nicht ausgef&uuml;llt sind.';

$_lang['setting_office_ms2_date_format'] = 'Datums-Format';
$_lang['setting_office_ms2_date_format_desc'] = 'Format verwendet die Syntax der PHP Funktion strftime(). Zum Beispiel, "%d.%m.%y %H:%M".';
$_lang['setting_office_ms2_order_grid_fields'] = 'Felder der Tabelle der Bestellung';
$_lang['setting_office_ms2_order_grid_fields_desc'] = 'Komma separierte Liste mit felder in der Tabelle der Bestellung. Verf&uuml;gbar: "createdon,updatedon,num,cost,cart_cost,delivery_cost,weight,status,delivery,payment,customer,receiver".';
$_lang['setting_office_ms2_order_form_fields'] = 'Hauptfelder des Bestellungsformulars';
$_lang['setting_office_ms2_order_form_fields_desc'] = 'Komma separierte Liste mit Hauptfeldern der Bestellung, die im ersten Tab angezeigt werden. Verf&uuml;gbar: "weight,createdon,updatedon,cart_cost,delivery_cost,status,delivery,payment".';
$_lang['setting_office_ms2_order_address_fields'] = 'Felder der K&aauml;uferadresse';
$_lang['setting_office_ms2_order_address_fields_desc'] = 'Komma separierte Liste mit Adressfeldern, die im dritten Tab angezeigt werden. Verf&uuml;gbar: "receiver,phone,index,country,region,metro,building,city,street,room". Falls leer, wird der Tab nicht angezeigt..';
$_lang['setting_office_ms2_order_product_fields'] = 'Felder der gekauften Produkte.';
$_lang['setting_office_ms2_order_product_fields_desc'] = 'Komma separierte Liste mit Feldern, die im angezeigt werden. Verf&uuml;gbar: "count,price,weight,cost,options". Produktfelder mit dem Prefix "product_", z.B. "product_pagetitle,product_article". Zus&aauml;tzlich k&ouml;nnen Werte von Optionsfeldern mit dem Prefix "_option" angezeigt werden, z.B. "option_color,option_size".';

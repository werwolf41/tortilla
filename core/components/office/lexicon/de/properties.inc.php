<?php

$_lang['office_prop_action'] = 'Name des Kontrollers zum Starten. Erforderlich.';

$_lang['office_prop_HybridAuth'] = 'Einschalten der HybridAuth Integration, falls installiert.';
$_lang['office_prop_providers'] = 'Komma separierte Liste von HybridAuth Providern f&uuml;r die Authentifizierung. Alle verf&uuml;gbaren Provider: {core_path}components/hybridauth/model/hybridauth/lib/Providers/. Zum Beispiel, &providers=`Google,Twitter,Facebook`.';
$_lang['office_prop_groups'] = 'Komma separierte Liste existierender Benutzergruppen, denen Benutzer beim ersten Login zugeordnet werden. Zum Beispiel, &groups=`Users:1` f&uuml;gt neue Benutzer der Gruppe "Users" mit der Rolle "Member" hinzu.';
$_lang['office_prop_rememberme'] = 'Falls TRUE wird der Benutzer dauerhaft wiedererkannt.';
$_lang['office_prop_loginContext'] = 'Kontext der Authentifizierung. Default: aktueller Kontext.';
$_lang['office_prop_addContexts'] = 'Komma separierte Liste zus&aauml;tzlicher Kontexte f&uuml;r die Authentifizierung, Zum Beispiel: &addContexts=`web,ru,en`';

$_lang['office_prop_loginResourceId'] = 'Ressource ID f&uuml;r die Weiterleitung nach erfolgreicher Anmeldung. Default: 0 - redirect auf sich selber.';
$_lang['office_prop_logoutResourceId'] = 'Ressource ID f&uuml;r die Weiterleitung nach erfolgreichem Abmelden. Default: 0 - redirect auf sich selber.';

$_lang['office_prop_tplLogin'] = 'Diesen Chunk sehen alle anonymen User.';
$_lang['office_prop_tplLogout'] = 'Diesen Chunk sehen alle angemeldeten User.';
$_lang['office_prop_tplActivate'] = 'Chunk f&uuml;r Aktiverungsemail.';
$_lang['office_prop_tplRegister'] = 'Chunk f&uuml;r Registrierungsemail.';
$_lang['office_prop_tplProfile'] = 'Chunk f&uuml;r das Userprofil.';
$_lang['office_prop_providerTpl'] = 'Chunk um den Link f&uuml;r HybridAuth anzuzeigen. Sowohl f&uuml;r die Autorisierung als auch f&uuml;r das Linking des Services zum Konto.';
$_lang['office_prop_activeProviderTpl'] = 'Chunk f&uuml;r das Icon der verlinkten HybridAuth Services.';

$_lang['office_prop_linkTTL'] = 'Time to live f&uuml;r Aktivierungslink.';

$_lang['office_prop_profileFields'] = 'Komma separierte Liste der erlaubten User Feldern, die ge&aauml;ndert werden d&uuml;rfen. Mit Angabe der maximalen L&aauml;nge an Zeichen. Zum Beispiel, &profileFields=`username:25,fullname:50,email`.';
$_lang['office_prop_requiredFields'] = 'Komma separierte Liste von erforderlichen User Feldern, die ben&ouml;tigt werden, damit ein Profil geupdated werden kann. Zum Beispiel, &requiredFields=`username,fullname,email`.';

$_lang['office_prop_avatarPath'] = 'Ordner, um die Avatars in MODX_ASSETS_PATH zu speichern. Default: "images/users/".';
$_lang['office_prop_avatarParams'] = 'JSON String mit Parametern f&uuml;r Avatar-Anpassungen mittels phpThumb. Default: "{"w":200,"h":200,"zc":0,"bg":"ffffff","f":"jpg"}".';

$_lang['office_prop_remote'] = 'Obligatorisches Adresse einer Seite auf einem Remote Server, um das Snippet "officeAuthServer" auszuf&uuml;hren.';
$_lang['office_prop_key'] = 'Obligatorischer Daten Verschl&uuml;sselungs Keys, um die &uuml;bertragene Information zu sch&uuml;tzen. Der Key muss f&uuml;r den Client und den Server der selbe sein.';
$_lang['office_prop_createUser'] = 'Erlaube das Anlegen neuer Benutzer.';
$_lang['office_prop_updateUser'] = 'Erlaube updaten von existierenden Benutzerdaten von einem Remote Server.';
$_lang['office_prop_authId'] = 'ID der Page dieser Seite f&uuml;r die Benutzer-Authentifizierung. Nach erfolgreicher Authentifizierung muss dieser zum Snippet "officeRemoteServer" zur&uuml;ckgeleitet werden.';
$_lang['office_prop_hosts'] = 'Komma separierte Liste mit Domains, die die Login Page Zugriff haben d&uuml;rfen.';

<?php

$_lang['office_auth_login'] = 'Anmelden';
$_lang['office_auth_remote_login'] = 'Remote login';
$_lang['office_auth_logout'] = 'Ausloggen';
$_lang['office_auth_send'] = 'Senden';
$_lang['office_auth_form_header'] = 'Bei ihrem Konto anmelden';
$_lang['office_auth_form_footer'] = 'Wir werden ihnen ein Link f&uuml;r Ihr Login senden.';
$_lang['office_auth_welcome'] = 'Sie sind angemeldet als ';

$_lang['office_auth_login'] = 'Anmdelden';
$_lang['office_auth_login_email'] = 'Email';
$_lang['office_auth_login_email_desc'] = 'Geben Sie ihre Emailadresse an, die Sie f&uuml;r die Registrierung benutzt haben.';
$_lang['office_auth_login_username'] = 'Anmelden';
$_lang['office_auth_login_username_desc'] = 'Geben Sie die Emailadresse, das Login oder die Telefonnummer ein, die sie f&uuml;r die Registrierung benutzt haben.';
$_lang['office_auth_login_password'] = 'Passwort';
$_lang['office_auth_login_password_desc'] = 'Falls Sie Ihr Passwort vergessen haben, lassen Sie dieses Feld einfach leer. Sie erhalten dann ein neues Passwort zusammen mit einem Aktivierungslink.';
$_lang['office_auth_login_ha'] = 'Soziale Netzwerke';
$_lang['office_auth_login_phone'] = 'Handynummer';
$_lang['office_auth_login_phone_desc'] = 'Ihre Handynummer';
$_lang['office_auth_login_phone_code'] = 'SMS Code';
$_lang['office_auth_login_phone_code_desc'] = 'Geben Sie den R&uuml;cksetzungscode ein, den Sie erhalten haben.';

$_lang['office_auth_login_ha_desc'] = 'Sie k&ouml;nnen die schnelle Anmeldung &uuml;ber das Soziale Netzwerk benutzen, vorausgesetzt, dass Sie Sich bereits per Email registriert haben und gewisse Services mit ihrem Konto verkn&uuml;pft haben.';
$_lang['office_auth_login_btn'] = 'Anmelden';

$_lang['office_auth_register'] = 'Registration';
$_lang['office_auth_register_username'] = 'Benutzername';
$_lang['office_auth_register_username_desc'] = 'Sie k&ouml;nnen einen Benutzername definieren, den Sie anstelle der Email benutzen k&ouml;nnen.';
$_lang['office_auth_register_email'] = 'Email';
$_lang['office_auth_register_email_desc'] = 'Sie erhalten einen Aktivierungslink auf die eingegebene Emailadresse.';
$_lang['office_auth_register_phone'] = 'Mobiltelefon';
$_lang['office_auth_register_phone_desc'] = 'Die Nummer ihres Mobiltelefons.';
$_lang['office_auth_register_phone_code'] = 'SMS code';
$_lang['office_auth_register_phone_code_desc'] = 'Bitte geben Sie den Aktivierungscode ein, den Sie erhalten haben.';
$_lang['office_auth_register_password'] = 'Passwort';
$_lang['office_auth_register_password_desc'] = 'Sie k&ouml;nnen Ihr eigenes Passwort festlegen oder das Feld leer lassen. Dann wird es f&uuml;r Sie automatisch generiert.';
$_lang['office_auth_register_fullname'] = 'Name';
$_lang['office_auth_register_fullname_desc'] = 'Vor- und Nachname';
$_lang['office_auth_register_btn'] = 'Registration';

$_lang['office_auth_email_subject'] = 'Aktivierungslink';

$_lang['office_auth_err_email_ns'] = 'Sie m&uuml;ssen Ihre Emailadresse eingeben.';
$_lang['office_auth_err_email_username_ns'] = 'Sie m&uuml;ssen Ihre Emailadresse, die Mobilnummer oder den Benutzernamen angeben.';
$_lang['office_auth_err_email_nf'] = 'Ein Benutzer mit der angegebenen Emailadresse existiert nicht.';
$_lang['office_auth_err_email_username_nf'] = 'Ein solcher Benutzer wurde nicht gefunden.';
$_lang['office_auth_err_user_active'] = 'Ihr Konto ist noch nicht aktiviert. Wir haben Ihnen erneut einen Aktivierungslink gesendet.';
$_lang['office_auth_err_email_exists'] = 'Ein Benutzer mit dieser Emailadresse existiert bereits.';
$_lang['office_auth_err_username_exists'] = 'Ein Benutzer mit diesem Benutzernamen existiert bereits.';
$_lang['office_auth_err_phone_exists'] = 'Ein Benutzer mit dieser Telefonnummer existiert bereits.';
$_lang['office_auth_err_password_short'] = 'Das Passwort ist zu kurz. Es muss mindestens [[+req]] Zeichen lang sein.';
$_lang['office_auth_err_password_invalid'] = 'Das Passwort enth&aauml;lt ung&uuml;ltige Zeichen';
$_lang['office_auth_err_username_invalid'] = 'Der Benutzername enth&aauml;lt ung&uuml;ltige Zeichen';
$_lang['office_auth_err_phone_invalid'] = 'Falsche Telefonnummer angegeben.';
$_lang['office_auth_err_phone_code_invalid'] = 'Falscher Aktivierungscode angegeben.';
$_lang['office_auth_err_email'] = 'Falsche Emailadresse';
$_lang['office_auth_err_create'] = 'Fehler beim Erstellen des Benutzers: [[+errors]]';
$_lang['office_auth_err_update'] = 'Fehler beim Aktualisieren des Benutzers: [[+errors]]';
$_lang['office_auth_err_send'] = 'Email konnte nicht gesendet werden: [[+errors]]';
$_lang['office_auth_err_login'] = 'Fehler beim Anmelden: [[+errors]]';
$_lang['office_auth_err_sms'] = 'Best&aauml;tigungscode konnte nicht gesendet werden: [[+errors]]';
$_lang['office_auth_err_sms_provider'] = 'Konnte den SMS Versender nicht laden.';
$_lang['office_auth_err_already_email_sent'] = 'Wir haben Ihnen k&uuml;rzlich eine Email gesendet. Sie m&uuml;ssen den darin enthaltenen Aktiverungslink anklicken. Falls Sie die Email nicht erhalten haben, pr&uuml;fen Sie Ihren Spam-Ordner.';
$_lang['office_auth_err_already_phone_sent'] = 'Wir haben Ihnen eine SMS geschickt. Sie m&uuml;ssen mit dem darin enthaltenen Code Ihr Konto aktivieren.';
$_lang['office_auth_err_already_logged'] = 'Sie sind bereis angemeldet.';
$_lang['office_auth_err_sudo_user'] = 'Dieser Benutzer muss sich &uuml;ber den Manager anmelden.';
$_lang['office_auth_err_create_disabled'] = 'Registration neuer Benutzer ist ausgeschaltet.';
$_lang['office_auth_err_remote_required'] = 'Adresse des entfernten Servers mit Snippet  "officeRemoteServer". Zum Beispiel "&remote=`http://site.com/remote/login`.';
$_lang['office_auth_err_key_required'] = 'Sie m&uuml;ssen einen nicht leeren Schl&uuml;ssel angeben, um die Bentuzerdaten zu sch&uuml;tzen. Zum Beispiel: "&key=`8Hy76Jkw`".';
$_lang['office_auth_err_ha_disabled'] = 'Sie m&uuml;ssen zuerst dieses Soziale Netzwerk mit ihrem Konto verbinden!';
$_lang['office_auth_success'] = 'Sie haben Sich erfolgreich angemeldet!';
$_lang['office_auth_password_success'] = 'Das neue Passwort ist aktiviert. Sie k&ouml;nnen Sich anmelden!';

$_lang['office_auth_email_send'] = 'Anmeldelink wurde gesendet. Pr&uuml;fen Sie Ihre Emails.';
$_lang['office_auth_phone_send'] = 'Der Best&aauml;tigungscode wurde an Ihr Mobiltelefon gesendet.';

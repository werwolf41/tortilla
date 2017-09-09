<?php

$_lang['office_auth_login'] = 'Sign in';
$_lang['office_auth_remote_login'] = 'Remote login';
$_lang['office_auth_logout'] = 'Logout';
$_lang['office_auth_send'] = 'Send';
$_lang['office_auth_form_header'] = 'Sign in to your account';
$_lang['office_auth_form_footer'] = 'We will send you a link to login to the site';
$_lang['office_auth_welcome'] = 'You are logged in as ';

$_lang['office_auth_login'] = 'Log in';
$_lang['office_auth_login_email'] = 'Email';
$_lang['office_auth_login_email_desc'] = 'Enter the email address you used when registering.';
$_lang['office_auth_login_username'] = 'Login';
$_lang['office_auth_login_username_desc'] = 'Enter the email, login or phone number, that you used for registration.';
$_lang['office_auth_login_password'] = 'Password';
$_lang['office_auth_login_password_desc'] = 'If you do not remember your password, simply leave this field blank and you will receive a new, along with a link to activate.';
$_lang['office_auth_login_ha'] = 'Social networks';
$_lang['office_auth_login_phone'] = 'Mobile phone';
$_lang['office_auth_login_phone_desc'] = 'Just the number of your mobile phone.';
$_lang['office_auth_login_phone_code'] = 'SMS code';
$_lang['office_auth_login_phone_code_desc'] = 'Please enter the reset code received at the specified phone number.';

$_lang['office_auth_login_ha_desc'] = 'You can use the quick login through the social network, provided that you`ve already registered through the mail and tied some of the services to their account.';
$_lang['office_auth_login_btn'] = 'Log in';

$_lang['office_auth_register'] = 'Registration';
$_lang['office_auth_register_username'] = 'Login';
$_lang['office_auth_register_username_desc'] = 'You can specify a separate login instead of using email for enter.';
$_lang['office_auth_register_email'] = 'Email';
$_lang['office_auth_register_email_desc'] = 'You will will receive an activation link on the specified email.';
$_lang['office_auth_register_phone'] = 'Mobile phone';
$_lang['office_auth_register_phone_desc'] = 'Just the number of your mobile phone.';
$_lang['office_auth_register_phone_code'] = 'SMS code';
$_lang['office_auth_register_phone_code_desc'] = 'Please enter the confirmation code received at the specified phone number.';
$_lang['office_auth_register_password'] = 'Password';
$_lang['office_auth_register_password_desc'] = 'You can specify your own password, or leave this field blank, and it will be generated automatically.';
$_lang['office_auth_register_fullname'] = 'Name';
$_lang['office_auth_register_fullname_desc'] = 'Your full name for displaying on site.';
$_lang['office_auth_register_btn'] = 'Registration';

$_lang['office_auth_email_subject'] = 'The authorization link';

$_lang['office_auth_err_email_ns'] = 'You must specify your email';
$_lang['office_auth_err_email_username_ns'] = 'You must specify your login, email or phone number';
$_lang['office_auth_err_email_nf'] = 'A user with the specified email is not found';
$_lang['office_auth_err_email_username_nf'] = 'A user is not found';
$_lang['office_auth_err_user_active'] = 'You have not activated your account, so we sent again you a link to activate.';
$_lang['office_auth_err_email_exists'] = 'A user with this email is already exists';
$_lang['office_auth_err_username_exists'] = 'A user with this login is already exists';
$_lang['office_auth_err_phone_exists'] = 'A user with this phone is already exists';
$_lang['office_auth_err_password_short'] = 'The password is too short. The minimum length of the password: [[+req]] characters.';
$_lang['office_auth_err_password_invalid'] = 'The password contains invalid characters';
$_lang['office_auth_err_username_invalid'] = 'The username contains invalid characters';
$_lang['office_auth_err_phone_invalid'] = 'Wrong phone number specified';
$_lang['office_auth_err_phone_code_invalid'] = 'Wrong activation code specified';
$_lang['office_auth_err_email'] = 'Wrong email';
$_lang['office_auth_err_create'] = 'Could not create the new user: [[+errors]]';
$_lang['office_auth_err_update'] = 'Could not update the user: [[+errors]]';
$_lang['office_auth_err_send'] = 'Could not send email: [[+errors]]';
$_lang['office_auth_err_login'] = 'Error when logging in: [[+errors]]';
$_lang['office_auth_err_sms'] = 'Could not send confirmation code: [[+errors]]';
$_lang['office_auth_err_sms_provider'] = 'Could not load the provider of sms.';
$_lang['office_auth_err_already_email_sent'] = 'We recently sent you a link - you need to activate it. If you do not receive the email, check your spam folder.';
$_lang['office_auth_err_already_phone_sent'] = 'We recently sent you the sms code - you need to activate it.';
$_lang['office_auth_err_already_logged'] = 'You are already logged in the system.';
$_lang['office_auth_err_sudo_user'] = 'This user must login via manager.';
$_lang['office_auth_err_create_disabled'] = 'Registration of new users is disabled.';
$_lang['office_auth_err_remote_required'] = 'Enter the page of a remote server with called snippet "officeRemoteServer". For example "&remote=`http://site.com/remote/login`.';
$_lang['office_auth_err_key_required'] = 'You must specify the same nonempty keys at server and client for protect user data. For example "&key=`8Hy76Jkw`".';
$_lang['office_auth_err_ha_disabled'] = 'You must first bind this social network to your account!';
$_lang['office_auth_success'] = 'You have successfully logged on the website!';
$_lang['office_auth_password_success'] = 'The new password is activated, you can log in!';

$_lang['office_auth_email_send'] = 'Login link on the site sent. Check your email.';
$_lang['office_auth_phone_send'] = 'The authorization code was sent to your phone number.';
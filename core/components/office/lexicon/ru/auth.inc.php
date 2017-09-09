<?php

$_lang['office_auth_login'] = 'Вход';
$_lang['office_auth_remote_login'] = 'Вход через удалённый сервис';
$_lang['office_auth_logout'] = 'Выход';
$_lang['office_auth_send'] = 'Отправить';
$_lang['office_auth_form_header'] = 'Вход в аккаунт';
$_lang['office_auth_form_footer'] = 'Мы пришлём вам ссылку для входа на сайт';
$_lang['office_auth_welcome'] = 'Вы авторизованы как ';

$_lang['office_auth_login'] = 'Авторизация';
$_lang['office_auth_login_email'] = 'Email';
$_lang['office_auth_login_email_desc'] = 'Укажите email, который вы использовали при регистрации.';
$_lang['office_auth_login_username'] = 'Логин';
$_lang['office_auth_login_username_desc'] = 'Укажите логин, email или номер телефона, которые вы использовали при регистрации.';
$_lang['office_auth_login_password'] = 'Пароль';
$_lang['office_auth_login_password_desc'] = 'Если вы не помните свой пароль - просто оставьте это поле пустым и вы получите новый, вместе со ссылкой на активацию.';
$_lang['office_auth_login_phone'] = 'Телефон';
$_lang['office_auth_login_phone_desc'] = 'Просто номер вашего сотового телефона.';
$_lang['office_auth_login_phone_code'] = 'Код из смс';
$_lang['office_auth_login_phone_code_desc'] = 'Пожалуйста, введите код сброса пароля, полученный на указанный номер телефона.';
$_lang['office_auth_login_ha'] = 'Социальные сети';
$_lang['office_auth_login_ha_desc'] = 'Вы можете использовать быстрый вход через социальные сети, при условии, что вы уже зарегистрировались через почту и привязали какой-то из сервисов к своей учётной записи.';
$_lang['office_auth_login_btn'] = 'Вход';

$_lang['office_auth_register'] = 'Регистрация';
$_lang['office_auth_register_username'] = 'Логин';
$_lang['office_auth_register_username_desc'] = 'Вы можете указать отдельный логин, чтобы не использовать email для входа.';
$_lang['office_auth_register_email'] = 'Email';
$_lang['office_auth_register_email_desc'] = 'На указанный email придёт письмо со ссылкой для активации.';
$_lang['office_auth_register_phone'] = 'Телефон';
$_lang['office_auth_register_phone_desc'] = 'Просто номер вашего сотового телефона.';
$_lang['office_auth_register_phone_code'] = 'Код из смс';
$_lang['office_auth_register_phone_code_desc'] = 'Пожалуйста, введите код подтверждения регистрации, полученный на указанный номер телефона.';
$_lang['office_auth_register_password'] = 'Пароль';
$_lang['office_auth_register_password_desc'] = 'Вы можете указать свой пароль, или оставить это поле пустым и тогда он будет сгенерирован автоматически.';
$_lang['office_auth_register_fullname'] = 'Имя';
$_lang['office_auth_register_fullname_desc'] = 'Ваше имя, для отображения на сайте.';
$_lang['office_auth_register_btn'] = 'Регистрация';

$_lang['office_auth_email_subject'] = 'Ссылка для авторизации';

$_lang['office_auth_err_email_ns'] = 'Вы должны указать email';
$_lang['office_auth_err_email_username_ns'] = 'Вы должны указать email, логин или номер телефона';
$_lang['office_auth_err_email_nf'] = 'Пользователь с указанным email не найден';
$_lang['office_auth_err_email_username_nf'] = 'Пользователь не найден';
$_lang['office_auth_err_user_active'] = 'Вы еще не активировали свой аккаунт, поэтому мы снова отправили вам ссылку на активацию.';
$_lang['office_auth_err_email_exists'] = 'Пользователь с таким email уже существует';
$_lang['office_auth_err_username_exists'] = 'Пользователь с таким логином уже существует';
$_lang['office_auth_err_phone_exists'] = 'Пользователь с таким номером телефона уже существует';
$_lang['office_auth_err_password_short'] = 'Слишком короткий пароль. Минимальная длин пароля: [[+req]] символов.';
$_lang['office_auth_err_password_invalid'] = 'Пароль содержит недопустимые символы';
$_lang['office_auth_err_username_invalid'] = 'Имя пользователя содержит недопустимые символы';
$_lang['office_auth_err_phone_invalid'] = 'Указан неправильный номер телефона';
$_lang['office_auth_err_phone_code_invalid'] = 'Указан неправильный код активации';
$_lang['office_auth_err_email'] = 'Неверный email';
$_lang['office_auth_err_create'] = 'Не могу создать нового пользователя: [[+errors]]';
$_lang['office_auth_err_update'] = 'Не могу обновить пользователя: [[+errors]]';
$_lang['office_auth_err_send'] = 'Не могу отправить письмо: [[+errors]]';
$_lang['office_auth_err_sms'] = 'Не могу отправить код подтверждения: [[+errors]]';
$_lang['office_auth_err_sms_provider'] = 'Не могу загрузить провайдера смс сообщений.';
$_lang['office_auth_err_login'] = 'Ошибка при входе в систему: [[+errors]]';
$_lang['office_auth_err_already_email_sent'] = 'Мы недавно уже отправили вам ссылку - нужно её активировать. Если письма всё еще нет, проверьте папку "Спам", возможно оно там.';
$_lang['office_auth_err_already_phone_sent'] = 'Мы недавно уже отправили вам sms код - нужно его активировать.';
$_lang['office_auth_err_already_logged'] = 'Вы уже авторизованы в системе.';
$_lang['office_auth_err_sudo_user'] = 'Этот пользователь должен входить через панель управления.';
$_lang['office_auth_err_create_disabled'] = 'Регистрация новых пользователей отключена.';
$_lang['office_auth_err_remote_required'] = 'Укажите страницу удалённого сервера с вызовом сниппета "officeRemoteServer". Например "&remote=`http://site.com/remote/login`".';
$_lang['office_auth_err_key_required'] = 'Вы должны указать одинаковые непустые ключи на сервере и клиенте для защиты данных пользователей. Например "&key=`8Hy76Jkw`".';
$_lang['office_auth_err_ha_disabled'] = 'Вы должны сначала привязать эту соц.сеть к вашей учётной записи!';
$_lang['office_auth_success'] = 'Вы успешно авторизовались на сайте!';
$_lang['office_auth_password_success'] = 'Новый пароль активирован, вы можете авторизоваться!';

$_lang['office_auth_email_send'] = 'Ссылка для входа на сайт отправлена. Проверьте вашу почту.';
$_lang['office_auth_phone_send'] = 'Код авторизации был отправлен на ваш номер телефона.';
<?php
$_lang['area_ec_main'] = 'Основные';
$_lang['area_ec_mail'] = 'Почта';
$_lang['area_ec_rating'] = 'Оценки и рейтинг';
$_lang['area_ec_gravatar'] = 'Gravatar';
$_lang['area_ec_antispam'] = 'Защита от спама';

$_lang['setting_ec_show_templates'] = 'Показывать вкладку у ресурсов с перечисленными шаблонами';
$_lang['setting_ec_show_templates_desc'] = 'Список id шаблонов через запятую, укажите *, что бы показывать у всех шаблонов';

$_lang['setting_ec_show_resources'] = 'Показывать вкладку у перечисленных ресурсов';
$_lang['setting_ec_show_resources_desc'] = 'Список id ресурсов через запятую, укажите *, что бы показывать у всех ресурсов';

$_lang['setting_ec_frontend_css'] = 'Стили фронтенда';
$_lang['setting_ec_frontend_css_desc'] = 'Путь к файлу со стилями. Если вы хотите использовать собственные стили - укажите путь к ним здесь, или очистите параметр и загрузите их вручную через шаблон сайта';

$_lang['setting_ec_frontend_js'] = 'Скрипты фронтенда';
$_lang['setting_ec_frontend_js_desc'] = 'Путь к файлу со скриптами. Если вы хотите использовать собственные скрипты - укажите путь к ним здесь, или очистите параметр и загрузите их вручную через шаблон сайта';

$_lang['setting_ec_thread_grid_fields'] = 'Список полей цепочек в списке';
$_lang['setting_ec_thread_grid_fields_desc'] = 'Поля, которые будут видны в админке в списке цепочек, через запятую';

$_lang['setting_ec_thread_window_fields'] = 'Список полей при редактировании цепочки';
$_lang['setting_ec_thread_window_fields_desc'] = 'Поля, которые будут видны в админке при редактировании цепочки, через запятую';

$_lang['setting_ec_message_grid_fields'] = 'Список полей сообщений в списке';
$_lang['setting_ec_message_grid_fields_desc'] = 'Поля, которые будут видны в админке в списке сообщений, через запятую';

$_lang['setting_ec_message_window_layout'] = 'Разметка окна редактирования сообщения';
$_lang['setting_ec_message_window_layout_desc'] = 'Строка в JSON формате';

$_lang['setting_ec_auto_reply_author'] = 'Автоматически заполнять Автор ответа';
$_lang['setting_ec_auto_reply_author_desc'] = 'При ответе на сообщение будет автоматически заполнен Автор ответа';

//$_lang['setting_ec_use_richtext'] = 'Использовать TinyMCE для поля Ответ';
//$_lang['setting_ec_use_richtext_desc'] = 'Обязательно наличие установленного TinyMCE';


$_lang['setting_ec_mail_notify_user'] = 'Отправлять уведомления пользователям';
$_lang['setting_ec_mail_notify_user_desc'] = 'Если пользователь оставит свою электронную почту, ему будет отправлено уведомление о том, что он оставил сообщение на сайте';

$_lang['setting_ec_mail_notify_manager'] = 'Отправлять уведомления администраторам';
$_lang['setting_ec_mail_notify_manager_desc'] = 'Уведомлять администратора о новых сообщениях на сайте';

$_lang['setting_ec_mail_new_subject_user'] = 'Тема письма-уведомления пользователю о новом сообщения';
$_lang['setting_ec_mail_new_subject_user_desc'] = 'Можно переопределить через параметр newEmailSubjUser сниппета ecForm';

$_lang['setting_ec_mail_new_subject_manager'] = 'Тема письма-уведомления администратора о новом сообщения';
$_lang['setting_ec_mail_new_subject_manager_desc'] = 'Можно переопределить через параметр newEmailSubjManager сниппета ecForm';

$_lang['setting_ec_mail_update_subject_user'] = 'Тема письма-уведомления пользователю об изменении его сообщения (опубликовано или дан ответ)';
$_lang['setting_ec_mail_update_subject_user_desc'] = 'Можно переопределить через параметр updateEmailSubjUser сниппета ecForm';

$_lang['setting_ec_mail_manager'] = 'Электронная почта администратора для уведомления';
$_lang['setting_ec_mail_manager_desc'] = 'Если пусто - будет использована системная настройка emailsender';

$_lang['setting_ec_mail_from'] = 'С какого адреса отправлять уведомления о новых сообщениях';
$_lang['setting_ec_mail_from_desc'] = 'Если пусто - будет использована системная настройка emailsender';

$_lang['setting_ec_mail_from_name'] = 'От чьего имени отправлять уведомления о новых сообщениях';
$_lang['setting_ec_mail_from_name_desc'] = 'Если пусто - будет использована системная настройка site_name';


$_lang['setting_ec_rating_max'] = 'Максимально возможная оценка';
$_lang['setting_ec_rating_max_desc'] = 'Используется для ограничения введенных пользователями данных';

$_lang['setting_ec_rating_wilson_confidence'] = 'Доверительный уровень для рейтинга по Вильсону';
$_lang['setting_ec_rating_wilson_confidence_desc'] = 'Доверительный уровень. 1.0 = 85%, 1.6 = 95%. См. <a href="https://habrahabr.ru/company/darudar/blog/143188/" target="_blank">https://habrahabr.ru/company/darudar/blog/143188/</a>';

$_lang['setting_ec_rating_visual_editor'] = 'Визуальное отображение рейтинга в менеджере';
$_lang['setting_ec_rating_visual_editor_desc'] = 'Отображать рейтинг звездочками или просто числами в админке';

$_lang['setting_ec_gravatar_size'] = 'Размер иконки аватара';
$_lang['setting_ec_gravatar_default'] = 'Иконка аватара по-умолчанию';

$_lang['setting_ec_captcha_enable'] = 'Включить каптчу (Google ReCaptcha v2)';
$_lang['setting_ec_captcha_enable_desc'] = '';

$_lang['setting_ec_recaptcha2_api'] = 'Google ReCaptcha v2 API url';

$_lang['setting_ec_recaptcha2_site_key'] = 'Google ReCaptcha v2 site key';

$_lang['setting_ec_recaptcha2_secret_key'] = 'Google ReCaptcha v2 secret';
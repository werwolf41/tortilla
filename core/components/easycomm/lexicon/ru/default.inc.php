<?php
include_once 'setting.inc.php';

$_lang['easycomm']      = 'Сообщения';
$_lang['ec']            = 'Сообщения';
$_lang['ec_menu_desc']  = 'Управление сообщениями от пользователей';

$_lang['ec_threads']            = 'Цепочки';
$_lang['ec_threads_intro_msg']  = 'Вы можете выделять сразу несколько цепочек при помощи Shift или Ctrl.';

$_lang['ec_thread_id']              = 'Id';
$_lang['ec_thread_resource']        = 'Ресурс';
$_lang['ec_thread_name']            = 'Имя';
$_lang['ec_thread_title']           = 'Заголовок';
$_lang['ec_thread_count']           = 'Кол-во сообщений';
$_lang['ec_thread_rating_simple']   = 'Рейтинг (Среднее)';
$_lang['ec_thread_rating_wilson']   = 'Рейтинг (Вильсон)';
$_lang['ec_thread_extended']        = 'Дополнительно (JSON)';

$_lang['ec_thread_create']              = 'Новая Цепочка';
$_lang['ec_thread_update']              = 'Изменить';
$_lang['ec_thread_enable']              = 'Включить Цепочку сообщений';
$_lang['ec_threads_enable']             = 'Включить Цепочки сообщений';
$_lang['ec_thread_disable']             = 'Отключить Цепочку сообщений';
$_lang['ec_threads_disable']            = 'Отключить Цепочки сообщений';
$_lang['ec_threads_manage_messages']    = 'Управление сообщениями этой цепочки';
$_lang['ec_thread_remove']              = 'Уничтожить цепочку';
$_lang['ec_threads_remove']             = 'Уничтожить цепочки';
$_lang['ec_thread_remove_confirm']      = 'Вы уверены, что хотите уничтожить эту цепочку сообщений? Все сообщения, относящиеся к ней, будут уничтожены без возможности восстановления!';
$_lang['ec_threads_remove_confirm']     = 'Вы уверены, что хотите уничтожить эти цепочки сообщений? Все сообщения, относящиеся к ним, будут уничтожены без возможности восстановления!';

$_lang['ec_thread_err_name']    = 'Вы должны указать имя цепочки';
$_lang['ec_thread_err_ae']      = 'Цепочка с таким именем уже существует.';
$_lang['ec_thread_err_nf']      = 'Цепочка не найдена.';
$_lang['ec_thread_err_ns']      = 'Цепочка не указана.';
$_lang['ec_thread_err_remove']  = 'Ошибка при удалении цепочки.';
$_lang['ec_thread_err_save']    = 'Ошибка при сохранении цепочки.';


$_lang['ec_messages']           = 'Сообщения';
$_lang['ec_messages_intro_msg'] = 'Вы можете выделять сразу несколько сообщений при помощи Shift или Ctrl.';

$_lang['ec_message_id']                 = 'Id';
$_lang['ec_message_thread']             = 'Цепочка';
$_lang['ec_message_thread_name']        = 'Цепочка';
$_lang['ec_message_thread_resource']    = 'Id ресурса';
$_lang['ec_message_resource_pagetitle'] = 'Ресурс';
$_lang['ec_message_subject']            = 'Тема';
$_lang['ec_message_date']               = 'Дата';
$_lang['ec_message_user_name']          = 'Автор';
$_lang['ec_message_user_email']         = 'Эл. почта';
$_lang['ec_message_user_contacts']      = 'Контакты';
$_lang['ec_message_text']               = 'Текст';
$_lang['ec_message_published']          = 'Опубликовано';
$_lang['ec_message_reply_author']       = 'Автор ответа';
$_lang['ec_message_reply_text']         = 'Ответ';
$_lang['ec_message_notify']             = 'Уведомить о публикации (или ответе) пользователя по почте';
$_lang['ec_message_notify_date']        = 'Посл. уведомление';
$_lang['ec_message_extended']           = 'Дополнительно (JSON)';
$_lang['ec_message_ip']                 = 'IP адрес';
$_lang['ec_message_rating']             = 'Оценка (от 1 до 5)';

$_lang['ec_message_create']     = 'Создать';
$_lang['ec_message_update']     = 'Изменить';
$_lang['ec_message_publish']    = 'Опубликовать сообщение';
$_lang['ec_messages_publish']   = 'Опубликовать сообщения';
$_lang['ec_message_unpublish']  = 'Снять с публикации';
$_lang['ec_messages_unpublish'] = 'Снять с публикации сообщения';

$_lang['ec_message_view_on_site'] = 'Посмотреть на сайте';

$_lang['ec_message_delete']             = 'Удалить сообщение';
$_lang['ec_messages_delete']            = 'Удалить сообщения';
$_lang['ec_message_delete_confirm']     = 'Вы уверены, что хотите удалить это сообщение? В дальнейшем его можно будет восстановить.';
$_lang['ec_messages_delete_confirm']    = 'Вы уверены, что хотите удалить эти сообщения? В дальнейшем их можно будет восстановить.';

$_lang['ec_message_undelete']           = 'Восстановить сообщение';
$_lang['ec_messages_undelete']          = 'Восстановить сообщения';
$_lang['ec_message_undelete_confirm']   = 'Вы уверены, что хотите восстановить это сообщение?';
$_lang['ec_messages_undelete_confirm']  = 'Вы уверены, что хотите восстановить эти сообщения?';

$_lang['ec_message_remove']             = 'Уничтожить сообщение';
$_lang['ec_messages_remove']            = 'Уничтожить сообщения';
$_lang['ec_message_remove_confirm']     = 'Вы уверены, что хотите безвозвратно уничтожить это сообщение?';
$_lang['ec_messages_remove_confirm']    = 'Вы уверены, что хотите безвозвратно уничтожить эти сообщения?';

$_lang['ec_message_tab_main']   = 'Сообщение';
$_lang['ec_message_tab_reply']      = 'Ответ';
$_lang['ec_message_tab_settings']   = 'Настройки';


$_lang['ec_message_err_thread']         = 'Вы должны указать Цепочку';
$_lang['ec_message_err_user_name']      = 'Вы должны указать Имя';
$_lang['ec_message_err_user_email']     = 'Вы должны указать Электронную почту';
$_lang['ec_message_err_user_contacts']  = 'Вы должны указать Контактную информацию';
$_lang['ec_message_err_subject']        = 'Вы должны указать Тему';
$_lang['ec_message_err_rating']         = 'Вы должны поставить Оценку';
$_lang['ec_message_err_text']           = 'Вы должны написать Текст сообщения';
$_lang['ec_message_err_captcha']        = 'Поставьте галочку "Я не робот"';
$_lang['ec_message_err_nf']             = 'Сообщение не найдена.';
$_lang['ec_message_err_ns']             = 'Сообщение не указано.';
$_lang['ec_message_err_remove']         = 'Ошибка при удалении Сообщения';
$_lang['ec_message_err_save']           = 'Ошибка при сохранении Сообщения';

$_lang['ec_object_published'] = 'Опубликован';

$_lang['ec_grid_search']    = 'Поиск';
$_lang['ec_grid_actions']   = 'Действия';

$_lang['ec_fe_message_add']             = 'Написать сообщение';
$_lang['ec_fe_message_user_name']       = 'Ваше имя';
$_lang['ec_fe_message_user_email']      = 'Электронная почта';
$_lang['ec_fe_message_user_contacts']   = 'Контактная информация';
$_lang['ec_fe_message_subject']         = 'Тема сообщения';
$_lang['ec_fe_message_rating']          = 'Оценка';
$_lang['ec_fe_message_rating_0']        = 'Пожалуйста, оцените по 5 бальной шкале';
$_lang['ec_fe_message_rating_1']        = 'Плохо';
$_lang['ec_fe_message_rating_2']        = 'Есть и получше';
$_lang['ec_fe_message_rating_3']        = 'Средне';
$_lang['ec_fe_message_rating_4']        = 'Хорошо';
$_lang['ec_fe_message_rating_5']        = 'Отлично! Рекомендую!';
$_lang['ec_fe_message_text']            = 'Ваше сообщение';
$_lang['ec_fe_send']                    = 'Отправить';
$_lang['ec_fe_send_success']            = 'Ваше сообщение было успешно отправлено. Оно будет опубликовано после одобрения модератором сайта!';

$_lang['ec_fe_message_antismap']    = 'Антиспам поле. Его необходимо скрыть через css';
$_lang['ec_fe_spam_detected']       = 'Обнаружена попытка спама';

$_lang['ec_unknown_action']         = 'Неизвестное действие';

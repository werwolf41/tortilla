<?php
include_once 'setting.inc.php';

$_lang['superFilter'] = 'Фильтры';
$_lang['superFilter_menu'] = 'Фильтры';
$_lang['superFilter_menu_desc'] = 'Управление фильтрами';


# base
$_lang['seofilter'] = 'Управление SEO фильтром';
$_lang['seoFilter'] = 'SEO фильтр';
$_lang['seofilter_menu_desc'] = 'Управление параметрами фильтра.';
$_lang['seofilter_intro_msg'] = 'Вы можете выделять сразу несколько строк при помощи Shift или Ctrl.';
$_lang['seofilter_unknown_action'] = 'Неизвестное действие';
$_lang['seofilter_request_error'] = 'Ошибка запроса';
$_lang['seofilter_param_type_not_supported'] = 'Данный тип параметра не поддерживается';


# common
$_lang['seofilter_err_empty'] = 'Вы должны заполнить поле.';
$_lang['seofilter_err_unique'] = 'Это поле уникально, такая запись уже существует.';

$_lang['seofilter_err_nf'] = 'Запись не найдена.';
$_lang['seofilter_err_ns'] = 'Запись не указана.';
$_lang['seofilter_err_remove'] = 'Ошибка при удалении записи.';
$_lang['seofilter_err_save'] = 'Ошибка при сохранении записи.';

$_lang['seofilter_grid_filter'] = 'Фильтр...';
$_lang['seofilter_grid_search'] = 'Поиск...';
$_lang['seofilter_grid_actions'] = 'Действия';

# sfParam
$_lang['seofilter_params'] = 'Параметры';
$_lang['seofilter_param_id'] = 'Id';
$_lang['seofilter_param_name'] = 'Имя';
$_lang['seofilter_param_type'] = 'Тип';
$_lang['seofilter_param_type_field'] = 'Простое поле товара';
$_lang['seofilter_param_type_field_json'] = 'JSON поле товара';
$_lang['seofilter_param_type_vendor'] = 'Производитель';

$_lang['seofilter_param_create'] = 'Добавить Параметр';
$_lang['seofilter_param_update'] = 'Изменить Параметр';
$_lang['seofilter_param_remove'] = 'Удалить Параметр';
$_lang['seofilter_params_remove'] = 'Удалить Параметры';
$_lang['seofilter_param_remove_confirm'] = 'Вы уверены, что хотите удалить этот Параметр?';
$_lang['seofilter_params_remove_confirm'] = 'Вы уверены, что хотите удалить эти Параметры?';

# sfPiece
$_lang['seofilter_pieces'] = 'Значения фильтров';
$_lang['seofilter_piece_id'] = 'Id';
$_lang['seofilter_piece_param'] = 'Параметр';
$_lang['seofilter_piece_value'] = 'Значение';
$_lang['seofilter_piece_alias'] = 'Алиас';
$_lang['seofilter_piece_correction'] = 'Уточнение значения';
$_lang['seofilter_piece_use_count'] = 'Кол-во использований';

$_lang['seofilter_piece_create'] = 'Добавить Значение';
$_lang['seofilter_piece_update'] = 'Изменить Значение';
$_lang['seofilter_piece_remove'] = 'Удалить Значение';
$_lang['seofilter_pieces_remove'] = 'Удалить Значение';
$_lang['seofilter_piece_remove_confirm'] = 'Вы уверены, что хотите удалить это Значение?';
$_lang['seofilter_pieces_remove_confirm'] = 'Вы уверены, что хотите удалить эти Значения?';

$_lang['seofilter_pieces_generate'] = 'Сгенерировать значения';
$_lang['seofilter_pieces_generate_err_no_filter'] = 'Сначала выберите в Фильтре, для какого параметра произвести генерацию!';
$_lang['seofilter_pieces_generate_confirm'] = 'Сгенерировать значения для указанного параметра?';

# sfPieceContent
$_lang['seofilter_pieces_content'] = 'Содержимое страниц-фильтра';
$_lang['seofilter_piece_content_id'] = 'Id';
$_lang['seofilter_piece_content_resource_id'] = 'Id категории';
$_lang['seofilter_piece_content_resource'] = 'Категория';
$_lang['seofilter_piece_content_alias'] = 'Алиас фильтра';
$_lang['seofilter_piece_content_pagetitle'] = 'Заголовок страницы';
$_lang['seofilter_piece_content_title'] = 'Заголовок';
$_lang['seofilter_piece_content_keywords'] = 'Ключевые слова';
$_lang['seofilter_piece_content_description'] = 'Описание';
$_lang['seofilter_piece_content_text1'] = 'Текст 1';
$_lang['seofilter_piece_content_text2'] = 'Текст 2';

$_lang['seofilter_piece_content_create'] = 'Добавить Содержимое';
$_lang['seofilter_piece_content_update'] = 'Изменить запись';
$_lang['seofilter_piece_content_remove'] = 'Удалить запись';
$_lang['seofilter_pieces_content_remove'] = 'Удалить записи';
$_lang['seofilter_piece_content_remove_confirm'] = 'Вы уверены, что хотите удалить это содержимое?';
$_lang['seofilter_pieces_content_remove_confirm'] = 'Вы уверены, что хотите удалить эти записи?';

$_lang['seofilter_piece_content_tab_general'] = 'Общие';
$_lang['seofilter_piece_content_tab_text1'] = 'Текст 1';
$_lang['seofilter_piece_content_tab_text2'] = 'Текст 2';
$_lang['seofilter_piece_content_tab_seo'] = 'Сео параметры';

# sfDefaultContent
$_lang['seofilter_default_content'] = 'Тексты по-умолчанию';
$_lang['seofilter_default_content_id'] = 'Id';
$_lang['seofilter_default_content_resource_id'] = 'Id категории';
$_lang['seofilter_default_content_resource'] = 'Категория';
$_lang['seofilter_default_content_children'] = 'Вкл. дочерние';
$_lang['seofilter_default_content_priority'] = 'Приоритет';
$_lang['seofilter_default_content_active'] = 'Активен';
$_lang['seofilter_default_content_text1'] = 'Текст 1';
$_lang['seofilter_default_content_text2'] = 'Текст 2';

$_lang['seofilter_default_content_create'] = 'Добавить Содержимое';
$_lang['seofilter_default_content_update'] = 'Изменить запись';
$_lang['seofilter_default_content_remove'] = 'Удалить запись';
$_lang['seofilter_defaults_content_remove'] = 'Удалить записи';
$_lang['seofilter_default_content_remove_confirm'] = 'Вы уверены, что хотите удалить эту запись?';
$_lang['seofilter_defaults_content_remove_confirm'] = 'Вы уверены, что хотите удалить эти записи?';

$_lang['seofilter_default_content_tab_general'] = 'Общие';
$_lang['seofilter_default_content_tab_text1'] = 'Текст 1';
$_lang['seofilter_default_content_tab_text2'] = 'Текст 2';

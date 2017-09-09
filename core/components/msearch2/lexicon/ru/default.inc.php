<?php
/**
 * Default Russian Lexicon Entries for mSearch2
 *
 * @package msearch2
 * @subpackage lexicon
 */

include_once 'setting.inc.php';

$_lang['msearch2'] = 'mSearch2';
$_lang['mse2_menu_desc'] = 'Настройки поиска на вашем сайте';
$_lang['mse2_tab_search'] = 'Поиск';
$_lang['mse2_tab_search_intro'] = 'Здесь вы можете проверить, как работает поиск у вас на сайте.';
$_lang['mse2_tab_index'] = 'Индекс';
$_lang['mse2_tab_index_intro'] = 'Этот раздел отвечает за управление поисковым индексом. В зависимости от мощности сервера, можно указывать сколько страниц индексировать за один запрос.';
$_lang['mse2_tab_queries'] = 'Запросы';
$_lang['mse2_tab_queries_intro'] = 'В этой таблице вы видите поисковые запросы на вашем сайте. Если у вас много нерелевантных запросов, возможно вам стоит изменить настройки индексации или добавить синонимы.';
$_lang['mse2_tab_aliases'] = 'Синонимы';
$_lang['mse2_tab_aliases_intro'] = 'Вы можете указать синонимы для слов, которые будут автоматически подставляться в запрос. Синоним может заменять указанное слово - будет полезно для исправления пользовательских опечаток, например "wiskas &rarr; whiskas".';
$_lang['mse2_tab_dictionaries'] = 'Словари';
$_lang['mse2_tab_dictionaries_intro'] = 'Установите нужные словари из предложенного списка. Для нормальной работы mSearch2 у вас должен быть хотя бы один словарь. После изменения списка словарей сайт нужно обязательно проиндексировать заново.';

$_lang['mse2_err_no_query'] = 'Задан пустой поисковый запрос.';
$_lang['mse2_err_no_query_var'] = 'Не указан поисковый запрос.';
$_lang['mse2_err_min_query'] = 'Слишком короткий поисковый запрос.';
$_lang['mse2_err_no_results'] = 'Подходящих результатов не найдено.';

$_lang['mse2_search'] = 'Поиск на сайте';
$_lang['mse2_search_clear'] = 'Очистить';
$_lang['mse2_intro'] = 'Превью';
$_lang['mse2_weight'] = 'Вес';
$_lang['mse2_show_unpublished'] = 'Неопубликованные';
$_lang['mse2_show_deleted'] = 'Удалённые';

$_lang['mse2_index_create'] = 'Обновить индекс';
$_lang['mse2_index_clear'] = 'Очистить индекс';

$_lang['mse2_index_total'] = 'Всего страниц';
$_lang['mse2_index_indexed'] = 'Проиндексировано страниц';
$_lang['mse2_index_words'] = 'Проиндексировано слов';
$_lang['mse2_index_limit'] = 'Индексировать по n страниц';
$_lang['mse2_index_offset'] = 'Пропустить от начала';

$_lang['mse2_filter_resource_isfolder'] = 'Контейнер';
$_lang['mse2_filter_resource_class_key'] = 'Класс документа';
$_lang['mse2_filter_ms_price'] = 'Цена';
$_lang['mse2_filter_ms_vendor'] = 'Производитель';
$_lang['mse2_filter_ms_new'] = 'Новый';
$_lang['mse2_filter_resource_parent'] = 'Категория';
$_lang['mse2_filter_boolean_yes'] = 'Да';
$_lang['mse2_filter_boolean_no'] = 'Нет';
$_lang['mse2_filter_number_min'] = 'От';
$_lang['mse2_filter_number_max'] = 'До';
$_lang['mse2_filter_total'] = 'Всего результатов:';
$_lang['mse2_filter_msoption_color'] = 'Цвета';
$_lang['mse2_filter_msoption_size'] = 'Размеры';

$_lang['mse2_filter_month_01'] = 'Январь';
$_lang['mse2_filter_month_02'] = 'Февраль';
$_lang['mse2_filter_month_03'] = 'Март';
$_lang['mse2_filter_month_04'] = 'Апрель';
$_lang['mse2_filter_month_05'] = 'Май';
$_lang['mse2_filter_month_06'] = 'Июнь';
$_lang['mse2_filter_month_07'] = 'Июль';
$_lang['mse2_filter_month_08'] = 'Август';
$_lang['mse2_filter_month_09'] = 'Сентябрь';
$_lang['mse2_filter_month_10'] = 'Октябрь';
$_lang['mse2_filter_month_11'] = 'Ноябрь';
$_lang['mse2_filter_month_12'] = 'Декабрь';

$_lang['mse2_sort'] = 'Сортировка:';
$_lang['mse2_sort_asc'] = 'по возрастанию';
$_lang['mse2_sort_desc'] = 'по убыванию';
$_lang['mse2_sort_publishedon'] = 'Дата публикации';
$_lang['mse2_sort_price'] = 'Цена';
$_lang['mse2_limit'] = 'Показывать на странице';
$_lang['mse2_select'] = 'Выберите из списка';
$_lang['mse2_selected'] = 'Вы выбрали';
$_lang['mse2_reset'] = 'Сбросить';
$_lang['mse2_submit'] = 'Отправить';
$_lang['mse2_more'] = 'Загрузить еще';

$_lang['mse2_err_no_filters'] = 'Нечего фильтровать';

$_lang['mse2_chunk_default'] = 'Стандартный вид';
$_lang['mse2_chunk_alternate'] = 'Альтернативный вид';

$_lang['mse2_query'] = 'Запрос';
$_lang['mse2_query_quantity'] = 'Количество запросов';
$_lang['mse2_query_found'] = 'Количество результатов';
$_lang['mse2_query_remove'] = 'Удалить запрос';
$_lang['mse2_query_remove_all'] = 'Удалить все запросы';
$_lang['mse2_query_remove_all_confirm'] = 'Вы действительно хотите удалить все поисковые запросы? Эта операция необратима!';
$_lang['mse2_query_search'] = 'Поиск по запросам';

$_lang['mse2_alias'] = 'Синоним';
$_lang['mse2_alias_word'] = 'Исходное слово';
$_lang['mse2_alias_replace'] = 'Заменяет';
$_lang['mse2_alias_create'] = 'Добавить синоним';
$_lang['mse2_alias_update'] = 'Изменить псевдоним';
$_lang['mse2_alias_remove'] = 'Удалить псевдоним';
$_lang['mse2_alias_search'] = 'Поиск по синонимам';
$_lang['mse2_alias_err_rq'] = 'Это поле обязательно для заполнения.';
$_lang['mse2_alias_err_eq'] = 'Синоним ничем не отличается от исходного слова.';
$_lang['mse2_alias_err_ae'] = 'Синоним "[[+alias]]" для слова "[[+word]]" уже задан.';

$_lang['mse2_dictionary'] = 'Словарь';
$_lang['mse2_language'] = 'Язык';
$_lang['mse2_dictionary_installed'] = 'Установлен';
$_lang['mse2_dictionary_russian'] = 'Русский';
$_lang['mse2_dictionary_english'] = 'Английский';
$_lang['mse2_dictionary_german'] = 'Немецкий';
$_lang['mse2_dictionary_ukrainian'] = 'Украинский';
$_lang['mse2_dictionary_estonian'] = 'Эстонский';
$_lang['mse2_dictionary_install'] = 'Установить словарь';
$_lang['mse2_dictionary_remove'] = 'Удалить словарь';
$_lang['mse2_dictionary_mirror'] = 'Зеркало Sourceforge';
$_lang['mse2_dictionary_mirror_select'] = 'Выберите из списка';
$_lang['mse2_dictionary_err_ns'] = 'Не указан словарь для загрузки';
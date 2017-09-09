<?php
/**
 * Default English Lexicon Entries for mSearch2
 *
 * @package msearch2
 * @subpackage lexicon
 */

include_once 'setting.inc.php';

$_lang['msearch2'] = 'mSearch2';
$_lang['mse2_menu_desc'] = 'Search settings on your site';
$_lang['mse2_tab_search'] = 'Search';
$_lang['mse2_tab_search_intro'] = 'Here you can check how search work on your site.';
$_lang['mse2_tab_index'] = 'Index';
$_lang['mse2_tab_index_intro'] = 'This section is responsible for the management of the search index';
$_lang['mse2_tab_queries'] = 'Queries';
$_lang['mse2_tab_queries_intro'] = 'There you see a search queries on your site. If you have a lot of irrelevant queries, maybe you need to change the settings for indexing or add aliases.';
$_lang['mse2_tab_aliases'] = 'Aliases';
$_lang['mse2_tab_aliases_intro'] = 'Here you can add aliases for words, that will be added to query. Alias can replace the word, it may be useful for correcting users typos, for example "wiskas" &rarr; "whiskas".';
$_lang['mse2_tab_dictionaries'] = 'Dictionaries';
$_lang['mse2_tab_dictionaries_intro'] = 'Install the required dictionaries from the list provided. For normal operation mSearch2 you must be at least one dictionary. After changing the list of dictionaries you must reindex your site again.';

$_lang['mse2_err_no_query'] = 'Your search query is empty.';
$_lang['mse2_err_no_query_var'] = 'No search query.';
$_lang['mse2_err_min_query'] = 'Your search query is too short.';
$_lang['mse2_err_no_results'] = 'No results found.';

$_lang['mse2_search'] = 'Search on site';
$_lang['mse2_search_clear'] = 'Clear';
$_lang['mse2_intro'] = 'Preview';
$_lang['mse2_weight'] = 'Weight';
$_lang['mse2_show_unpublished'] = 'Unpublished';
$_lang['mse2_show_deleted'] = 'Deleted';

$_lang['mse2_index_create'] = 'Update index';
$_lang['mse2_index_clear'] = 'Clear index';

$_lang['mse2_index_total'] = 'Pages total';
$_lang['mse2_index_indexed'] = 'Pages in index';
$_lang['mse2_index_words'] = 'Words in index';
$_lang['mse2_index_limit'] = 'Page limit for index';
$_lang['mse2_index_offset'] = 'Page offset';

$_lang['mse2_filter_resource_isfolder'] = 'Container';
$_lang['mse2_filter_resource_class_key'] = 'Class of document';
$_lang['mse2_filter_ms_price'] = 'Price';
$_lang['mse2_filter_ms_vendor'] = 'Vendor';
$_lang['mse2_filter_ms_new'] = 'New';
$_lang['mse2_filter_resource_parent'] = 'Category';
$_lang['mse2_filter_boolean_yes'] = 'Yes';
$_lang['mse2_filter_boolean_no'] = 'No';
$_lang['mse2_filter_number_min'] = 'From';
$_lang['mse2_filter_number_max'] = 'To';
$_lang['mse2_filter_total'] = 'Total items:';
$_lang['mse2_filter_msoption_color'] = 'Colors';
$_lang['mse2_filter_msoption_size'] = 'Sizes';

$_lang['mse2_filter_month_01'] = 'January';
$_lang['mse2_filter_month_02'] = 'February';
$_lang['mse2_filter_month_03'] = 'March';
$_lang['mse2_filter_month_04'] = 'April';
$_lang['mse2_filter_month_05'] = 'May';
$_lang['mse2_filter_month_06'] = 'June';
$_lang['mse2_filter_month_07'] = 'July';
$_lang['mse2_filter_month_08'] = 'August';
$_lang['mse2_filter_month_09'] = 'September';
$_lang['mse2_filter_month_10'] = 'October';
$_lang['mse2_filter_month_11'] = 'November';
$_lang['mse2_filter_month_12'] = 'December';

$_lang['mse2_sort'] = 'Sort by:';
$_lang['mse2_sort_asc'] = 'ascending';
$_lang['mse2_sort_desc'] = 'descending';
$_lang['mse2_sort_publishedon'] = 'Published on';
$_lang['mse2_sort_price'] = 'Price';
$_lang['mse2_limit'] = 'Display on page';
$_lang['mse2_select'] = 'Select from list';
$_lang['mse2_selected'] = 'You have chosen';
$_lang['mse2_reset'] = 'Reset';
$_lang['mse2_submit'] = 'Submit';
$_lang['mse2_more'] = 'Load more';

$_lang['mse2_err_no_filters'] = 'Nothing to filter';

$_lang['mse2_chunk_default'] = 'Default view';
$_lang['mse2_chunk_alternate'] = 'Alternate view';

$_lang['mse2_query'] = 'Query';
$_lang['mse2_query_quantity'] = 'Number of queries';
$_lang['mse2_query_found'] = 'Number of results';
$_lang['mse2_query_remove'] = 'Remove query';
$_lang['mse2_query_remove_all'] = 'Remove all queries';
$_lang['mse2_query_remove_all_confirm'] = 'Are you sure you want to remove all search queries? This operation cannot be undone!';
$_lang['mse2_query_search'] = 'Search by queries';

$_lang['mse2_alias'] = 'Alias';
$_lang['mse2_alias_word'] = 'Source word';
$_lang['mse2_alias_replace'] = 'Replace';
$_lang['mse2_alias_create'] = 'Add alias';
$_lang['mse2_alias_update'] = 'Update alias';
$_lang['mse2_alias_remove'] = 'Remove alias';
$_lang['mse2_alias_search'] = 'Search by aliases';
$_lang['mse2_alias_err_rq'] = 'This field is required';
$_lang['mse2_alias_err_eq'] = 'Alias is equal to word.';
$_lang['mse2_alias_err_ae'] = 'Alias "[[+alias]]" is already set for word "[[+word]]".';

$_lang['mse2_dictionary'] = 'Dictionary';
$_lang['mse2_language'] = 'Language';
$_lang['mse2_dictionary_installed'] = 'Installed';
$_lang['mse2_dictionary_russian'] = 'Russian';
$_lang['mse2_dictionary_english'] = 'English';
$_lang['mse2_dictionary_german'] = 'German';
$_lang['mse2_dictionary_ukrainian'] = 'Ukrainian';
$_lang['mse2_dictionary_estonian'] = 'Estonian';
$_lang['mse2_dictionary_install'] = 'Install dictionary';
$_lang['mse2_dictionary_remove'] = 'Remove dictionary';
$_lang['mse2_dictionary_mirror'] = 'Sourceforge mirror';
$_lang['mse2_dictionary_mirror_select'] = 'Select an option';
$_lang['mse2_dictionary_err_ns'] = 'The dictionary not set';
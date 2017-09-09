<?php
/**
 * Properties English Lexicon Entries for mSearch2
 *
 * @package msearch2
 * @subpackage lexicon
 */

$_lang['mse2_prop_tpl'] = 'The chunk tpl to use for each row.';
$_lang['mse2_prop_limit'] = 'The number of results to limit.';
$_lang['mse2_prop_offset'] = 'An offset of resources returned by the criteria to skip.';
$_lang['mse2_prop_outputSeparator'] = 'An optional string to separate each tpl instance.';
$_lang['mse2_prop_toPlaceholder'] = 'If not empty, the snippet will save output to placeholder with that name, instead of return it to screen.';
$_lang['mse2_prop_toPlaceholders'] = 'If not empty, mFilter2 will save data in placeholders: "filters", "results" and "total" with prefix specified in this parameter. For example, you set &toPlaceholders=`my.` and will receive: [[+my.filters]], [[+my.results]] and [[+my.total]]';
$_lang['mse2_prop_toSeparatePlaceholders'] = 'Work the same as "&toPlaceholders" but placeholder "filters" split to separate placeholders with name of filter. For example, you set &toSeparatePlaceholders=`my.` and &filters=`tv|test,resource|pagetitle` - you receive placeholders: [[+my.results]], [[+my.total]], [[+my.tv|test]] and [[+my.resource|pagetitle]].';
//$_lang['mse2_prop_plPrefix'] = 'Used when placeholders set to global context by modX::setPlaceholder(). For example, snippet mSearch2 set placeolders "query" and "parents", that can be renamed by &queryVar=``, &parentsVar=`` and prefixed by &plPrefix. Parameters "&toPlaceholder" and such ignores this parameter, because there you specify its name.';

$_lang['mse2_prop_returnIds'] = 'Return comma-separated list of ids of matched resources.';
$_lang['mse2_prop_showLog'] = 'Display additional information about snippet work. Only for authenticated in context "mgr".';
$_lang['mse2_prop_fastMode'] = 'If enabled, then in chunk will be only received values ​​from the database. All raw tags of MODX, such as filters, snippets calls will be cut.';

$_lang['mse2_prop_parents'] = 'Container list, separated by commas, to search results. By default, the query is limited to the current parent. If set to 0, query not limited.';
$_lang['mse2_prop_depth'] = 'Integer value indicating depth to search for resources from each parent.';

$_lang['mse2_prop_includeTVs'] = 'An optional comma-delimited list of TemplateVar names to include in selection. For example "action,time" give you placeholders [[+action]] and [[+time]].';
$_lang['mse2_prop_tvPrefix'] = 'The prefix for TemplateVar properties, "tv." for example. By default it is empty.';

$_lang['mse2_prop_where'] = 'A JSON-style expression of criteria to build any additional where clauses from.';
$_lang['mse2_prop_showUnpublished'] = 'Show unpublished resources.';
$_lang['mse2_prop_showDeleted'] = 'Show deleted resources.';
$_lang['mse2_prop_showHidden'] = 'Show resources, that hidden in menu.';
$_lang['mse2_prop_hideContainers'] = 'Hide containers.';

$_lang['mse2_prop_introCutBefore'] = 'Specify the number of characters to be output in placeholder [[+intro]] before the first match in the text. The default value of "50".';
$_lang['mse2_prop_introCutAfter'] = 'Specify the number of characters to be output in placeholder [[+intro]] after the first match in the text. Default - "250".';

$_lang['mse2_prop_htagOpen'] = 'The opening tag for the highlight of found results in [[+intro]].';
$_lang['mse2_prop_htagClose'] = 'Closing tag for the highlight of found results in [[+intro]].';

$_lang['mse2_prop_minQuery'] = 'The minimum length of a search query.';
$_lang['mse2_prop_parentsVar'] = 'The name of the variable to additional filter by parents. Default is "parents", can be send with $_REQUEST.';
$_lang['mse2_prop_queryVar'] = 'The name of the variable of search query to get it from $_REQUEST. Default is "query"';

$_lang['mse2_prop_paginator'] = 'Snippet for pagination, default is "getPage".';
$_lang['mse2_prop_element'] = 'Snippet, which will be called to output the results of work. Default is "mSearch2".';
$_lang['mse2_prop_resources'] = 'List of resources for output, separated by commas. This list can be filtered by other parameters such as "parents", "showDeleted", "showHidden" and "showUnpublished".';
$_lang['mse2_prop_showEmptyFilters'] = 'Show filters when it has the only one item.';
$_lang['mse2_prop_sort'] = 'Comma separated list for sorting resources. It must be set in the form "table|field:direction". Default is "resource:publisedon:desc".';
$_lang['mse2_prop_filters'] = 'Comma separated list of filters. It must be set as "table|field:method". Default is "resource|parent:parents".';
$_lang['mse2_prop_aliases'] = 'Comma separated list of aliases for filters that will be used in page URL. It must be set as "table|field==alias". For example: "resource|parent==category".';
$_lang['mse2_prop_suggestions'] = 'If false, this option will disable the estimated number of results, which is displayed next to each filter. You can disable it if you are unhappy with filtration rate.';
$_lang['mse2_prop_suggestionsMaxFilters'] = 'The maximum number of filters, which are preliminary results. If the filter would be more suggestions are turned off.';
$_lang['mse2_prop_suggestionsMaxResults'] = 'The maximum number of resources for which preliminary results of the work. If resources will more suggestions are turned off.';
$_lang['mse2_prop_suggestionsRadio'] = 'Comma separated list of radio filters. The suggestions for these groups of filters will not be summarized.';

$_lang['mse2_prop_filter_delimeter'] = 'Delimiter of code name tables and fields of the filter. Default is "|"';
$_lang['mse2_prop_method_delimeter'] = 'Delimiter of full filter name and the method of processing. Default is ":';
$_lang['mse2_prop_values_delimeter'] = 'Delimiter of filter values in the address bar of the site. Default is ","';

$_lang['mse2_prop_tplOuter'] = 'Chunk for the whole block of filters and the results.';
$_lang['mse2_prop_tplFilter.outer.default'] = 'Standard chunk of one filters group.';
$_lang['mse2_prop_tplFilter.row.default'] = 'Standard chunk of a filter in the group. By default it look as checkbox.';

$_lang['mse2_prop_tpls'] = 'Comma-separated list of available chunks for rows template. You can switch these chunks by specify parameter "tpl" in $_REQUEST. 0 is a chunk default, and then in order. For example: "&tpls=`default,chunk1,chunk2`", for output of goods by "chunk1" you need to send a "$_REQUEST[tpl] = 1".';
$_lang['mse2_prop_tplWrapper'] = 'Name of a chunk serving as a wrapper template for the output. Placeholders: [[+output]], [[+total]], [[+query]] and [[+parents]].';
$_lang['mse2_prop_wrapIfEmpty'] = 'If true, will output the wrapper specified in &tplWrapper even if the output is empty.';
$_lang['mse2_prop_forceSearch'] = 'Search is required to display the results. If no search query - nothing displays.';

$_lang['mse2_prop_tplForm'] = 'Chunk with form for output.';
$_lang['mse2_prop_autocomplete'] = 'Configuring autocompletion. The options are: "results" - site search (for output will be called the snippet specified in the "element"), the "queries" - search by table with queries, the "0" - disable autocompletion.';
$_lang['mse2_prop_pageId'] = 'Id of the page that will be sent to the search query. By default, the current page.';

$_lang['mse2_prop_fields'] = 'List of indexed fields of the resource to search for. Each field you can specify the search weight through colon.';
$_lang['mse2_prop_onlyIndex'] = 'Search by index words, without the addition of bonuses for exact phrases in a simple search through LIKE.';
$_lang['mse2_prop_onlyAllWords'] = 'Display only those resources that match all words of the search query.';
$_lang['mse2_prop_showSearchLog'] = 'Show detailed search log when showLog is enabled.';

$_lang['mse2_prop_filterOptions'] = 'JSON string with variables for javascript filter. For example: {"pagination":"#mse2_pagination", "selected_values_delimeter":", "}';

$_lang['mse2_prop_ajaxMode'] = 'Mode of ajax pagination: "default", "button" or "scroll".';
$_lang['mse2_prop_cacheTime'] = 'Time of cache of filters that generated for current selected resources.';
<?php

// С каким полем будем работать?
$field = empty($field) ? 'content' : $field;

// проверка номера текущей страницы
$pageVarKey = empty($pageVarKey) ? 'page' : $pageVarKey;
$isPage = isset($_REQUEST[$pageVarKey]) && intval($_REQUEST[$pageVarKey]) > 0;
// если мы на странице X и выставлена настройка - текст скрывается
if($isPage && $modx->getOption("seofilter_hide_".$field."_paging", null, true)) {
    return '';
}

// определяем сам ресурс..
if(empty($resource)) {
    $resource = &$modx->resource;
}
else {
    $resource = $modx->getObject('modResource', intval($resource));
}
// ..значение поля
$text = $resource->get($field);

// если была подмена содержимого поля, то сразу возвращаем ее
if($modx->getPlaceholder('seo_filter_superseded_'.$field)) {
    return $text;
}

// мы на странице сео фильтра?
$isSeoFilterPage = intval($modx->getPlaceholder('seo_filters_count')) > 0;

// в содержимом поля есть сео плейсхолдер?
$hasFilterPlaceholder = false;
if($isSeoFilterPage) {
    $hasFilterPlaceholder = (strpos($text, "+seo_filter_") === false) ? false : true;
}

// TODO: где-то здесь добавить обработку sfDefaultContent
// ...
// ...
// ...


// если это сео фильтр..
if($isSeoFilterPage) {
    // ..и есть плейсхолдер - проверяем сист. настройку и скрываем поле, если надо
    if($hasFilterPlaceholder && $modx->getOption("seofilter_hide_".$field."_on_seo_filter_pls", null, false)) {
        return '';
    }
    // ..проверяем другую сист. настройку и скрываем поле, если надо
    elseif(!$hasFilterPlaceholder && $modx->getOption("seofilter_hide_".$field."_on_seo_filter", null, true)) {
        return '';
    }
}

return $text;
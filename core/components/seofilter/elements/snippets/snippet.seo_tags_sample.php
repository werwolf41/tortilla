<?php

// определяем страницу
if (!isset($pageVarKey)) {$pageVarKey = 'page';}
$page = 0;
$pageSuffix = '';
if(isset($_REQUEST[$pageVarKey])){
    $page = intval($_REQUEST[$pageVarKey]);
    if($page > 1) {
        $pageSuffix = ', страница '.$page;
    }
}

// проверяем, нет ли ручной установки meta страницы
if($modx->getPlaceholder('seo_filter_supersede')) {
    $modx->setPlaceholder('seoTitle', $modx->getPlaceholder('seo_filter_supersede_title').$pageSuffix);
    if(!empty($page)) {
        $modx->setPlaceholder('seoKeywords', $modx->getPlaceholder('seo_filter_supersede_keywords'));
        $modx->setPlaceholder('seoDescription', $modx->getPlaceholder('seo_filter_supersede_description'));
    }
    return '';
}

// определяем ресурс
if(empty($resource)) {
    $resource = &$modx->resource;
}
else {
    $resource = $modx->getObject('modResource', intval($resource));
}

$siteName = $modx->getOption('site_name');

$pagetitle = htmlspecialchars($resource->get("pagetitle"));
$longtitle = htmlspecialchars($resource->get("longtitle"));
$seotitle = htmlspecialchars($resource->getTVValue("seoTitle"));
$template = $resource->get("template");

$title = '';
$keywords = $resource->getTVValue("seoKeywords");
$description = $resource->getTVValue("seoDescription");
$addSiteName = false;

$smartTitle = $longtitle;
if(empty($smartTitle)) {
    $smartTitle = $pagetitle;
}

$seo_filter_title = $modx->getPlaceholder('seo_filter_title');
if(!empty($seo_filter_title)) {
    $smartTitle .= ' '.htmlspecialchars($seo_filter_title);
}

$title = $smartTitle;
if(!empty($seotitle)) {
    $title = $seotitle;
}

// Особые правил для шаблонов
switch($template) {
    default:
        $addSiteName = true;
        break;
}

// Добавляем страницу
$title = $title.$pageSuffix;

// Добавляем имя сайта
if($addSiteName) {
    $title = $title.'. '.$siteName;
}

// meta теги
if(empty($keywords)) {
    $keywords = $smartTitle;
}
if(empty($description)) {
    $description = $smartTitle.'. '.$siteName;
}
if($page > 1) {
    $keywords = '';
    $description = '';
}

$modx->setPlaceholder('seoTitle', $title);
$modx->setPlaceholder('seoKeywords', $keywords);
$modx->setPlaceholder('seoDescription', $description);

return '';
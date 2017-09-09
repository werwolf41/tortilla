<?php

$result = '';
// проверяем, нет ли ручной установки заголовка страницы
if($modx->getPlaceholder('seo_filter_supersede')) {
    $result = $modx->getPlaceholder('seo_filter_supersede_pagetitle');
}
else {
    if(empty($resource)) {
        $resource = & $modx->resource;
    }
    else {
        $resource = $modx->getObject('modResource', intval($resource));
    }

    if(!$resource) {
        return '';
    }

    $result = $resource->get("longtitle");
    if(empty($result)){
        $result = $resource->get("pagetitle");
    }

    $seo_filter_title = $modx->getPlaceholder('seo_filter_title');
    if(!empty($seo_filter_title)) {
        $result .= ' '.htmlspecialchars($seo_filter_title);
    }
}

$modx->setPlaceholder('pageTitle', $result);

return $result;
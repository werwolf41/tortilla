<?php
if ($modx->event->name == 'OnPageNotFound') {
    /** @var array $scriptProperties */
    /** @var seoFilter $seoFilter */
    if (!$seoFilter = $modx->getService('seoFilter', 'seoFilter', $modx->getOption('seofilter_core_path', null, $modx->getOption('core_path') . 'components/seofilter/') . 'model/seofilter/', $scriptProperties)) {
        return 'Could not load seoFilter class!';
    }

    $resourceId = $seoFilter->processUri();
    if ($resourceId) {
        // Есть такая виртульная страница,
        // получаем кастомные meta и содержимое
        $seoFilter->supersedeCategoryContent($resourceId);
        // и подсовывем ее юзеру
        $modx->sendForward($resourceId);
    }
}

if($modx->event->name == 'OnLoadWebDocument') {
    if($modx->getPlaceholder('seo_filter_supersede')) {
        // Делаем ресурс не кэшируемым
        $modx->resource->set('cacheable', 0);
        // подменяем поля с текстом
        $t1 = $modx->getOption('seofilter_text1_default_field');
        if(!empty($t1)) {
            $modx->resource->set($t1, $modx->getPlaceholder('seo_filter_supersede_text1'));
        }
        $t2 = $modx->getOption('seofilter_text2_default_field');
        if(!empty($t2)) {
            $modx->resource->set($t2, $modx->getPlaceholder('seo_filter_supersede_text2'));
        }
    }
}
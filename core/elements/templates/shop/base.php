<?php
// Получаем свойства текущего MODX-шаблона
$properties = $modx->resource->getOne('Template')->getProperties();
// Если в настройках шаблона не указано название файла-шаблона,
// то используем по умолчанию index.tpl
if(!empty($properties['tpl'])){
    $tpl = $properties['tpl'];
}
else{
    $tpl = 'index.tpl';
}
// Если документ не кешируемый, то отключаем кеширование Smarty
// (кеширование Smarty включается/выключается в настройках modxSmarty. По умолчанию отключено).
if ($modx->resource->cacheable != '1') {
    $modx->smarty->caching = false;
}
// Отрабатываем Smarty-шаблон и возвращаем результат
return $modx->smarty->fetch($tpl    );


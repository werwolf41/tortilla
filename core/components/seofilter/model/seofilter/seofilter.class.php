<?php

require_once (dirname(__FILE__) . '/piecesmap.class.php');

/**
 * The base class for seoFilter.
 */
class seoFilter {
	/* @var modX $modx */
	public $modx;

    private $piecesMap;

    private $max_depth = 1;

    private $filtersCount = 0;
    private $filterPieces = array();

    const AUTO_LABEL = 'A';

	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;

        $this->piecesMap = new piecesMap($modx);

        $this->max_depth = $this->modx->getOption('seofilter_max_depth', 1);

		$corePath = $this->modx->getOption('seofilter_core_path', $config, $this->modx->getOption('core_path') . 'components/seofilter/');
		$assetsUrl = $this->modx->getOption('seofilter_assets_url', $config, $this->modx->getOption('assets_url') . 'components/seofilter/');

		$connectorUrl = $assetsUrl . 'connector.php';

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'connectorUrl' => $connectorUrl,

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'chunksPath' => $corePath . 'elements/chunks/',
			'templatesPath' => $corePath . 'elements/templates/',
			'chunkSuffix' => '.chunk.tpl',
			'snippetsPath' => $corePath . 'elements/snippets/',
			'processorsPath' => $corePath . 'processors/',

            'json_response' => true,
            'disable_meta_paging' => $this->modx->getOption('seofilter_disable_meta_paging', $config, true),
		), $config);

		$this->modx->addPackage('seofilter', $this->config['modelPath']);
		$this->modx->lexicon->load('seofilter:default');
	}

    /**
     * Метод разбирает $uri и определяет, является ли эта страница категорией с фильтром
     * @param string $uri
     * @return bool|int|mixed
     */
    public function processUri($uri = '') {
        if(empty($uri)) {
            $request_param_alias = $this->modx->context->getOption('request_param_alias', 'q');
            if (!isset($_REQUEST[$request_param_alias])) {
                return false;
            }
            $uri = $_REQUEST[$request_param_alias];
        }

        $container_suffix = $this->modx->context->getOption('container_suffix', '');
        $request = $page = trim($uri, "/");
        $pieces = explode('/', $request);

        $section = null;
        for ($i = count($pieces); $i > 0; $i--) {
            // Определяем id раздела.
            if ($section = $this->modx->findResource($page.$container_suffix)) {
                break;
            }
            $page = trim(str_replace($pieces[$i-1], '', $page), "/");
        }

        if (!$section) {
            return false;
        }

        $pieces = explode('/', trim(str_replace($page, '', $request), '/'));

        if(count($pieces) > $this->max_depth) {
            return false;
        }

        $count = 0; // Обнуляем проверку
        $filter_title = array();
        foreach ($pieces as $piece) {
            if (empty($piece)){
                return false;
            }

            $pieceData = $this->piecesMap->findPieceData($piece);
            if(!$pieceData) {
                return false;
            }
            $param = $pieceData['param'];
            $value = $pieceData['value'];
            $title = $pieceData['title'];

            // устанавливаем плейсхолдер
            $this->modx->setPlaceHolder('seo_filter_'.$param, $title);
            $filter_title[] = $title;

            // Осталось выставить нужные переменные в запрос, как будто юзер их сам указал
            $_GET[$param] = $value;
            $_POST[$param] = $value;
            $_REQUEST[$param] = $value;

            $count++;
        }

        // Есть ли параметры
        if ($count > 0) {

            $this->filtersCount = $count;
            $this->filterPieces = $pieces;
            $this->modx->setPlaceHolder('seo_filter_title', implode(" ", $filter_title));
            $this->modx->setPlaceHolder('seo_filter_alias', implode('/', $pieces));
            $this->modx->setPlaceHolder('seo_filters_count', $count);

            // Возвращаем найденную категорию
            return $section;
        }

        return false;
    }

    public function findAlias($param, $value) {

        // можно отключить генерацию ссылки, если сейчас смотрим фильтр максимально возможной вложенности
        //if($this->filtersCount >= $this->max_depth) {
        //    return '';
        //}

        // получаем alias для ссылки первого уровня
        $alias = $this->piecesMap->findAlias($param, $value);
        if(empty($alias)) {
            return ':';
        }

        // получаем фильтры категории, жесткая связь с компонентом visualFilter
        $cacheOptions = array( xPDO::OPT_CACHE_KEY => 'default/visual_filter' );
        $cacheKey = 'category_'.$this->modx->resource->get('id').'_filters';

        $categoryFilters = $this->modx->cacheManager->get($cacheKey, $cacheOptions);

        // если в кеше есть данные о порядке фильтров,
        // то мы можем попробовать сгенерировать ссылки на фильтры уровня N
        if(!empty($categoryFilters)) {
            // сейчас смотрим страницу уже с фильтром, но не максимально возможного уровня
            if($this->filtersCount > 0 && $this->filtersCount < $this->max_depth) {
                $aliases = $this->piecesMap->findDeepAlias($categoryFilters, $this->filterPieces, $alias, $param, $value);
                if(!empty($aliases)) {
                    return $alias.':'.implode("/", $aliases);
                }
            }
        }

        // в остальных случаях просто генерируем ссылки на фильтры первого уровня

        return $alias.':'.$alias;

        // простой случай, фильтр первого уровня
        //if($this->filtersCount == 0) {
        //    return $this->piecesMap->findAlias($param, $value);
        //}

        //return '';
    }

    public function getCategoryContentAjax($data = array()){
        // uri и pageId - обязательные поля $data
        if(empty($data['uri']) || empty($data['pageId'])) {
            return $this->error($this->modx->lexicon('seofilter_request_error'));
        }
        /* @var $category modResource */
        $category = null;
        // пытаемся получить категорию, считая что к ее uri добавлен алиас фильтра
        $categoryId = $this->processUri($data['uri']);

        if(!$categoryId || !$category = $this->modx->getObject('msCategory', array('id' => $categoryId, 'published' => 1, 'deleted' => 0))) {
            // а нет такой категории с фильтром, тогда берем просто текущую категорию
            if(!isset($data['pageId']) || !$category = $this->modx->getObject('msCategory', array('id' => intval($data['pageId']), 'published' => 1, 'deleted' => 0))) {
                // и текущую не нашли, не судьба, выходим
                return $this->error($this->modx->lexicon('seofilter_request_error'));
            }
        }

        $result = $this->getCategoryFilterContent($category, true);
        if(!empty($result)) {
            return $this->success('', $result);
        }

        return $this->error($this->modx->lexicon('seofilter_request_error'));
    }

    public function supersedeCategoryContent($categoryId) {
        /* @var $category modResource */
        $category = $this->modx->getObject('msCategory', array('id' => $categoryId, 'published' => 1, 'deleted' => 0));
        if($category) {
            $contentData = $this->getCategoryFilterContent($category);
            if(!empty($contentData)) {
                $this->modx->setPlaceholder('seo_filter_supersede', '1');
                foreach($contentData as $k => $v) {
                    $this->modx->setPlaceholder('seo_filter_supersede_'.$k, $v);
                }
            }
        }
    }

    /**
     * Возвращает массив с meta и контентом для заданной категории с учетом фильтра
     *
     * @param $category modResource
     * @param $processElementTags bool
     *
     * @return array
     * */
    private function getCategoryFilterContent(& $category, $processElementTags = false){
        if($snippet = $this->modx->getOption('seofilter_before_get_content_snippet', null, '')) {
            $this->modx->runSnippet($snippet);
        }

        $fields = array('pagetitle', 'title', 'keywords', 'description', 'text1', 'text2');
        $parserMaxIterations = (integer) $this->modx->getOption('parser_max_iterations', null, 10);
        $result = array();
        // Шаг 0. Задаем значения по-умолчанию: авто
        foreach($fields as $field) {
            $result[$field] = seoFilter::AUTO_LABEL;
        }

        // Шаг 1. Ищем текст фильтра (piece content)
        $filter_alias = $this->modx->getPlaceholder('seo_filter_alias');
        if(!empty($filter_alias)) {
            $pieceContent = $this->modx->getObject('sfPieceContent', array('resource_id' => $category->get('id'), 'alias' => $filter_alias));
            if($pieceContent) {
                foreach($fields as $field) {
                    $result[$field] = $pieceContent->get($field);

                    if($result[$field] != seoFilter::AUTO_LABEL) {
                        if($processElementTags){
                            // parse all cacheable tags first
                            $this->modx->getParser()->processElementTags('', $result[$field], false, false, '[[', ']]', array(), $parserMaxIterations);
                            // parse all non-cacheable and remove unprocessed tags
                            $this->modx->getParser()->processElementTags('', $result[$field], true, true, '[[', ']]', array(), $parserMaxIterations);
                        }
                        $df = $this->modx->getOption('seofilter_'.$field.'_default_field', null, $field);
                        $this->modx->setPlaceholder('seo_filter_superseded_'.$df, true);
                    }
                }
            }
        }

        // page title
        if($result['pagetitle'] == seoFilter::AUTO_LABEL) {
            $result['pagetitle'] = $this->modx->runSnippet('pageTitle', array('resource' => $category->get('id')));
        }

        // meta tags
        $this->modx->runSnippet('seoTags', array('resource' => $category->get('id')));

        if($result['title'] == seoFilter::AUTO_LABEL) {
            $result['title'] = $this->modx->getPlaceholder('seoTitle');
        }
        if($result['keywords'] == seoFilter::AUTO_LABEL) {
            $result['keywords'] = $this->modx->getPlaceholder('seoKeywords');
        }
        if($result['description'] == seoFilter::AUTO_LABEL) {
            $result['description'] = $this->modx->getPlaceholder('seoDescription');
        }

        // Шаг 2. Ищем авто-текст (текст по-умолчанию)
        if(!empty($filter_alias)) {

            $defaultContent = $this->modx->getObject('sfDefaultContent', array('resource_id' => $category->get('id'), 'active' => true));
            if(!$defaultContent) {
                $parents = $this->modx->getParentIds($category->get('id'));
                foreach($parents as $categoryId) {
                    if($defaultContent = $this->modx->getObject('sfDefaultContent', array('resource_id' => $categoryId, 'active' => true, 'children' => true))){
                        break;
                    };
                }
            }

            if($defaultContent) {
                foreach(array('text1', 'text2') as $field) {
                    $df = $this->modx->getOption('seofilter_'.$field.'_default_field', null, $field);
                    if(!$this->modx->getPlaceholder('seo_filter_superseded_'.$df)){
                        $result[$field] = $defaultContent->get($field);

                        if($processElementTags){
                            // parse all cacheable tags first
                            $this->modx->getParser()->processElementTags('', $result[$field], false, false, '[[', ']]', array(), $parserMaxIterations);
                            // parse all non-cacheable and remove unprocessed tags
                            $this->modx->getParser()->processElementTags('', $result[$field], true, true, '[[', ']]', array(), $parserMaxIterations);
                        }
                        $df = $this->modx->getOption('seofilter_'.$field.'_default_field', null, $field);
                        $this->modx->setPlaceholder('seo_filter_superseded_'.$df, true);
                    }
                }
            }
        }


        // Шаг 3. Текст категории
        $text_fields = array('text1', 'text2');

        foreach($text_fields as $field) {
            if($result[$field] == seoFilter::AUTO_LABEL) {
                $text_default_field = $this->modx->getOption('seofilter_'.$field.'_default_field');
                if(!empty($text_default_field)) {
                    $result[$field] = $category->get($text_default_field);

                    if($processElementTags) {
                        $result[$field] = $this->modx->runSnippet('seoContent', array('field' => $text_default_field, 'resource' => $category->get('id')));
                    }

                    if($processElementTags){
                        // parse all cacheable tags first
                        $this->modx->getParser()->processElementTags('', $result[$field], false, false, '[[', ']]', array(), $parserMaxIterations);
                        // parse all non-cacheable and remove unprocessed tags
                        $this->modx->getParser()->processElementTags('', $result[$field], true, true, '[[', ']]', array(), $parserMaxIterations);
                    }
                }
                else {
                    $result[$field] = '';
                }
            }
        }

        return $result;
    }


    /**
     * This method returns an success of the action
     *
     * @param string $message A lexicon key for success message
     * @param array $data.Additional data, for example cart status
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     * */
    public function success($message = '', $data = array(), $placeholders = array()) {
        $response = array(
            'success' => true
            ,'message' => $this->modx->lexicon($message, $placeholders)
            ,'data' => $data
        );
        return $this->config['json_response']
            ? $this->modx->toJSON($response)
            : $response;
    }

    /**
     * This method returns an error of the cart
     *
     * @param string $message A lexicon key for error message
     * @param array $data.Additional data, for example cart status
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     */
    public function error($message = '', $data = array(), $placeholders = array()) {
        $response = array(
            'success' => false
            ,'message' => $this->modx->lexicon($message, $placeholders)
            ,'data' => $data
        );
        return $this->config['json_response']
            ? $this->modx->toJSON($response)
            : $response;
    }

}
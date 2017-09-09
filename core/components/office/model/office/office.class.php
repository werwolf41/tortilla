<?php

class Office
{
    /** @var modX $modx */
    public $modx;
    /** @var pdoTools $pdoTools */
    public $pdoTools;
    public $initialized = array();
    public $controllers = array();
    protected $modx23 = false;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('office_core_path', $config,
            $this->modx->getOption('core_path') . 'components/office/'
        );
        $assetsUrl = $this->modx->getOption('office_assets_url', $config,
            $this->modx->getOption('assets_url') . 'components/office/'
        );
        $assetsPath = MODX_ASSETS_PATH;

        $this->config = array_merge(array(
            '+core_path' => $this->modx->config['core_path'],
            '+base_path' => $this->modx->config['base_path'],
            '+assets_path' => $this->modx->config['assets_path'],
            '+manager_path' => $this->modx->config['manager_path'],
            '+assets_url' => $this->modx->config['assets_url'],
            '+manager_url' => $this->modx->config['manager_url'],

            'assetsPath' => $assetsPath . 'components/office/',
            'assetsCachePath' => $assetsPath . 'components/office/cache/',
            'assetsCacheUrl' => $assetsUrl . 'cache/',
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',

            'actionUrl' => $assetsUrl . 'action.php',
            'minifyUrl' => $assetsUrl . 'min.php',
            'controllersPath' => $corePath . 'controllers/',

            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'templatesPath' => $corePath . 'elements/templates/',
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'processorsPath' => $corePath . 'processors/',
        ), $config);

        $this->modx->addPackage('office', $this->config['modelPath']);
        $this->modx->lexicon->load('office:default');

        if (class_exists('pdoTools')) {
            $this->pdoTools = $this->modx->getService('pdoTools');
            $this->pdoTools->setConfig($this->config);
        } else {
            $this->pdoTools = false;
        }

        $version = $this->modx->getVersionData();
        $this->modx23 = !empty($version) && version_compare($version['full_version'], '2.3.0', '>=');
    }


    /**
     * Initializes Office into different contexts.
     *
     * @param string $ctx
     * @param array $scriptProperties
     *
     * @return bool
     */
    public function initialize($ctx = 'web', $scriptProperties = array())
    {
        $this->config = array_merge($this->config, $scriptProperties);
        $this->config['ctx'] = $ctx;
        if (!empty($this->initialized[$ctx])) {
            return true;
        }
        switch ($ctx) {
            case 'mgr':
                break;
            default:
                if (!defined('MODX_API_MODE') || !MODX_API_MODE) {
                    $config = $this->makePlaceholders($this->config);

                    $css = trim($this->modx->getOption('office_frontend_css'));
                    if (!empty($css) && preg_match('/\.css/i', $css)) {
                        $this->modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));
                    }

                    $js = trim($this->modx->getOption('office_frontend_js'));
                    if (!empty($js) && preg_match('#\.js#i', $js)) {
                        $reg = json_encode(array(
                            'cssUrl' => $this->config['cssUrl'],
                            'jsUrl' => $this->config['jsUrl'],
                            'actionUrl' => $this->config['actionUrl'],
                            'close_all_message' => $this->modx->lexicon('office_message_close_all'),
                            'pageId' => $this->modx->resource->id,
                        ));
                        $this->modx->regClientStartupScript(
                            '<script type="text/javascript">OfficeConfig=' . $reg . ';</script>', true
                        );
                        $this->modx->regClientScript(str_replace($config['pl'], $config['vl'], $js));
                    }
                }

                $this->initialized[$ctx] = true;
                break;
        }

        return true;
    }


    /**
     * Method loads custom controllers
     *
     * @var string $dir Directory for load controllers
     *
     * @return officeDefaultController|bool
     */
    public function loadController($name)
    {
        if (!class_exists('officeDefaultController')) {
            require 'controller.class.php';
        }

        $name = strtolower(trim($name));
        if (isset($this->controllers[$name])) {
            return $this->controllers[$name];
        }

        $extensions = $this->getExtensions();
        $controllersPath = isset($extensions[$name]) && is_string($extensions[$name])
            ? $extensions[$name]
            : $this->config['controllersPath'];

        $file = $controllersPath . $name . '.class.php';
        if (!file_exists($file)) {
            // For backward compatibility
            $file = $controllersPath . $name . '/' . $name . '.class.php';
        }

        if (file_exists($file)) {
            /** @noinspection PhpIncludeInspection */
            $class = include_once($file);
            if (!class_exists($class)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "[Office] Wrong controller at \"{$file}\"");
            } else {
                /** @var officeDefaultController $controller */
                $controller = new $class($this, $this->config);
                if ($controller instanceof officeDefaultController && $controller->initialize()) {
                    $this->controllers[$name] = $controller;

                    return $controller;
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,
                        "[Office] Controller \"{$class}\" in \"{$file}\" must be an instance of officeDefaultController"
                    );
                }
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[Office] Could not find controller at \"{$file}\"");
        }

        return false;
    }


    /**
     * Loads given action, if exists, and transfers work to it
     *
     * @param $action
     * @param array $scriptProperties
     *
     * @return bool
     */
    public function loadAction($action, $scriptProperties = array())
    {
        $tmp = explode('/', strtolower(trim(trim($action), '/')));
        if (count($tmp) == 1) {
            $name = $tmp[0];
            $action = '';
        } else {
            $name = array_shift($tmp);
            $action = implode('/', $tmp);
        }

        /** @var officeDefaultController $controller */
        if ($controller = $this->loadController($name)) {
            $controller->setDefault($scriptProperties);

            if (empty($action) || strtolower($action) == 'default') {
                $action = $controller->getDefaultAction();
            }
            if (method_exists($controller, $action)) {
                return $controller->$action($scriptProperties);
            } else {
                return "Could not find method \"{$action}\" in controller \"{$name}\"";
            }
        }

        return "Could not load controller \"{$name}\"";
    }


    /**
     * Shorthand for load and run an processor in this component
     *
     * @param string $action
     * @param array $scriptProperties
     * @param string $processorsPath
     *
     * @return mixed
     */
    function runProcessor($action = '', $scriptProperties = array(), $processorsPath = '')
    {
        $this->modx->error->errors = $this->modx->error->message = null;

        if (!$processorsPath) {
            $processorsPath = $this->config['processorsPath'];
        }

        return $this->modx->runProcessor($action, $scriptProperties, array(
                'processors_path' => $processorsPath,
            )
        );
    }


    /**
     * Transform array to placeholders
     *
     * @param array $array
     * @param string $plPrefix
     * @param string $prefix
     * @param string $suffix
     *
     * @return array
     */
    public function makePlaceholders(array $array = array(), $plPrefix = '', $prefix = '[[+', $suffix = ']]')
    {
        if ($this->pdoTools) {
            return $this->pdoTools->makePlaceholders($array, $plPrefix, $prefix, $suffix);
        }

        $result = array('pl' => array(), 'vl' => array());

        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $result = array_merge_recursive($result,
                    $this->makePlaceholders($v, $plPrefix . $k . '.', $prefix, $suffix));
            } else {
                $pl = $plPrefix . $k;
                $result['pl'][$pl] = $prefix . $pl . $suffix;
                $result['vl'][$pl] = $v;
            }
        }

        return $result;
    }


    /**
     * Merges and minimizes given scripts or css and returns raw content
     *
     * @param array $files
     *
     * @return mixed|bool
     */
    public function Minify($files = array())
    {
        if (empty($files)) {
            return false;
        }

        $_GET['f'] = implode(',', $files);

        $min_libPath = MODX_MANAGER_PATH . 'min/lib';
        @set_include_path($min_libPath . PATH_SEPARATOR . get_include_path());

        if (!class_exists('Minify')) {
            /** @noinspection PhpIncludeInspection */
            require_once MODX_MANAGER_PATH . 'min/lib/Minify.php';
        }
        if (!class_exists('Minify_Controller_MinApp')) {
            /** @noinspection PhpIncludeInspection */
            require_once MODX_MANAGER_PATH . 'min/lib/Minify/Controller/MinApp.php';
        }

        // Attempt to prevent suhosin issues
        @ini_set('suhosin.get.max_value_length', 4096);
        if (!file_exists(MODX_CORE_PATH . 'cache/minify')) {
            mkdir(MODX_CORE_PATH . 'cache/minify');
        }
        Minify::setCache(MODX_CORE_PATH . 'cache/minify');

        // Fall back to plain files if Minify fails
        $content = array();
        foreach ($files as $file) {
            if (strpos($file, MODX_BASE_PATH) === false) {
                $file = MODX_BASE_PATH . ltrim($file, '/');
                $content[] = file_get_contents($file);
            }
        }
        $content = implode("\n", $content);

        return $content;
    }


    /**
     * Merges, minimizes and registers javascript for use in controllers
     *
     * @param array $files
     * @param string $file
     *
     * @return bool
     */
    public function addClientJs($files = array(), $file = 'main/all')
    {
        if ($js = $this->Minify($files)) {
            if (!preg_match('#.*?\.js#i', $file)) {
                $file .= '.js';
            }

            if ($this->saveFile($file, $js, $this->config['assetsCachePath'])) {
                $this->modx->regClientScript($this->config['assetsCacheUrl'] . $file);

                return true;
            }
        }

        return false;
    }


    /**
     * Merges, minimizes and registers css for use in controllers
     *
     * @param array $files
     * @param string $file
     *
     * @return bool
     */
    public function addClientCss($files = array(), $file = 'main/all')
    {
        if ($css = $this->Minify($files)) {
            if (!preg_match('#.*?\.css#i', $file)) {
                $file .= '.css';
            }

            if ($this->saveFile($file, $css, $this->config['assetsCachePath'])) {
                $this->modx->regClientCSS($this->config['assetsCacheUrl'] . $file);

                return true;
            }
        }

        return false;
    }


    /**
     * Registers lexicon entries for use in controllers
     *
     * @param array $topics
     * @param string $file
     *
     * @return bool
     */
    public function addClientLexicon($topics = array(), $file = 'main/lexicon')
    {
        $topics = array_merge(array('core:default'), $topics);

        foreach ($topics as $topic) {
            $this->modx->lexicon->load($topic);
        }

        $entries = $this->modx->lexicon->fetch();
        $lang = '
            OfficeLexicon = {';
        $s = '';
        while (list($k, $v) = each($entries)) {
            $s .= "'$k': " . '"' . strtr($v, array(
                    '\\' => '\\\\',
                    "'" => "\\'",
                    '"' => '\\"',
                    "\r" => '\\r',
                    "\n" => '\\n',
                    '</' => '<\/',
                )) . '",';
        }
        $s = trim($s, ',');
        $lang .= $s . '
            };
            var _ = function(s,v) {
                return OfficeLexicon[s]
                if (v != null && typeof(v) == "object") {
                    var t = ""+OfficeLexicon[s];
                    for (var k in v) {
                        t = t.replace("[[+"+k+"]]",v[k]);
                    }
                    return t;
                } else return OfficeLexicon[s];
            }';
        if (!preg_match('#.*?\.js#i', $file)) {
            $file .= '.js';
        }
        if ($this->saveFile($file, $lang, $this->config['assetsCachePath'])) {
            $this->modx->regClientScript($this->config['assetsCacheUrl'] . $file);

            return true;
        }

        return false;
    }


    public function addClientExtJS()
    {
        $this->modx->regClientCSS($this->config['cssUrl'] . 'main/lib/ext-all-notheme.css');

        $config = $this->makePlaceholders($this->config);
        if ($css = $this->modx->getOption('office_extjs_css', null, '', true)) {
            $this->modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));
        } else {
            if ($this->modx23) {
                $this->modx->regClientCSS($this->config['cssUrl'] . 'main/lib/xtheme-modx.new.css');
            } else {
                $this->modx->regClientCSS($this->config['cssUrl'] . 'main/lib/xtheme-modx.old.css');
            }
            $this->modx->regClientCSS($this->config['cssUrl'] . 'main/lib/office-add.css');
            $this->modx->regClientCSS($this->config['cssUrl'] . 'main/lib/font-awesome.min.css');
        }

        $this->addClientJs(array(
            MODX_MANAGER_URL . 'assets/ext3/adapter/jquery/ext-jquery-adapter.js',
            MODX_MANAGER_URL . 'assets/ext3/ext-all.js',
        ), 'main/ext');

        $this->addClientJs(array(
            MODX_MANAGER_URL . 'assets/modext/core/modx.js',
        ), 'main/modx');

        $this->addClientJs(array(
            MODX_MANAGER_URL . 'assets/modext/core/modx.localization.js',
            MODX_MANAGER_URL . 'assets/modext/util/utilities.js',
            MODX_MANAGER_URL . 'assets/modext/util/datetime.js',
            MODX_MANAGER_URL . 'assets/modext/core/modx.component.js',
            MODX_MANAGER_URL . 'assets/modext/widgets/core/modx.panel.js',
            MODX_MANAGER_URL . 'assets/modext/widgets/core/modx.tabs.js',
            MODX_MANAGER_URL . 'assets/modext/widgets/core/modx.window.js',
            (file_exists(MODX_MANAGER_PATH . 'assets/modext/widgets/modx.treedrop.js')
                ? MODX_MANAGER_URL . 'assets/modext/widgets/modx.treedrop.js'
                : MODX_MANAGER_URL . 'assets/modext/widgets/core/modx.tree.js'
            ),
            MODX_MANAGER_URL . 'assets/modext/widgets/core/modx.combo.js',
            MODX_MANAGER_URL . 'assets/modext/widgets/core/modx.grid.js',
        ), 'main/widgets');

        $this->addClientJs(array(
            $this->config['jsUrl'] . 'main/extjs/default.js',
            $this->config['jsUrl'] . 'main/extjs/default.utils.js',
            $this->config['jsUrl'] . 'main/extjs/default.combo.js',
            $this->config['jsUrl'] . 'main/extjs/default.grid.js',
            $this->config['jsUrl'] . 'main/extjs/default.window.js',
        ), 'main/office');

        $this->modx->regClientScript('
        <script type="text/javascript">
        Ext.onReady(function() {
            OfficeExt.config.modx23 = ' . (int)$this->modx23 . ';
            OfficeExt.config.date_format = "' . str_replace('"', '\"',
                $this->modx->getOption('office_ms2_date_format')) . '";
        });
        </script>', true);
    }


    /**
     * @param $name
     * @param array $properties
     *
     * @return mixed|string
     */
    public function getChunk($name, array $properties = array())
    {
        return $this->pdoTools
            ? $this->pdoTools->getChunk($name, $properties)
            : $this->modx->getChunk($name, $properties);
    }


    /**
     * @param $name
     * @param $path
     *
     * @return bool
     */
    public function addExtension($name, $path)
    {
        if (is_string($path)) {
            $name = strtolower(trim($name));
            $path = trim($path);

            if ($setting = $this->modx->getObject('modSystemSetting', 'office_controllers_paths')) {
                if (!$value = json_decode($setting->get('value'), true)) {
                    $value = array();
                }
                $value[$name] = $path;
                $setting->set('value', json_encode($value));

                return $setting->save();
            }
        }

        return false;
    }


    /**
     * @param $name
     *
     * @return bool
     */
    public function removeExtension($name)
    {
        $name = trim(strtolower($name));
        if ($setting = $this->modx->getObject('modSystemSetting', 'office_controllers_paths')) {
            if (!$value = json_decode($setting->get('value'), true)) {
                $value = array();
            }
            unset($value[$name]);
            $setting->set('value', json_encode($value));

            return $setting->save();
        }

        return false;
    }


    /**
     * @return array|mixed
     */
    public function getExtensions()
    {
        $extensions = array();
        if ($setting = $this->modx->getObject('modSystemSetting', 'office_controllers_paths')) {
            if ($value = json_decode($setting->get('value'), true)) {
                if (is_array($value)) {
                    $config = $this->makePlaceholders($this->config);
                    foreach ($value as $k => $v) {
                        $extensions[$k] = str_replace($config['pl'], $config['vl'], $v);
                    }
                }
            }
        }

        return $extensions;
    }


    /**
     * @param $file
     * @param $data
     * @param string $path
     *
     * @return bool|int
     */
    protected function saveFile($file, $data, $path = '')
    {
        $file = trim(trim($file), '/');
        if (empty($path)) {
            $path = $this->config['assetsCachePath'];
        }
        $path = rtrim($path, '/');
        if (strpos($file, '/') !== false) {
            $tmp = explode('/', $file);
            $file = array_pop($tmp);
            foreach ($tmp as $v) {
                $path = $path . '/' . $v;
                @mkdir($path);
            }
        }

        return file_put_contents($path . '/' . $file, $data);
    }


    /**
     * @param string $phone
     *
     * @return bool|string
     */
    public function checkPhone($phone)
    {
        $phone = preg_replace('#[^0-9]#', '', $phone);
        if (strlen($phone) == 10) {
            $phone = '7' . $phone;
        } elseif (strlen($phone) == 11 && substr($phone, 0, 1) == 8) {
            $phone = '7' . substr($phone, 1);
        }
        if (!is_numeric($phone) || strlen($phone) < 11) {
            return false;
        }

        return $phone;
    }

}
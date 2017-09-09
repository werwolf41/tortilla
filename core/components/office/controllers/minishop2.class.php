<?php

class officeMS2Controller extends officeDefaultController
{

    /**
     * @param array $config
     */
    public function setDefault($config = array())
    {
        if (defined('MODX_ACTION_MODE') && MODX_ACTION_MODE && !empty($_SESSION['Office']['miniShop2'])) {
            $this->config = $_SESSION['Office']['miniShop2'];
            $this->config['json_response'] = true;
        } else {
            $this->config = array_merge(array(
                'tplOuter' => 'tpl.Office.ms2.outer',
            ), $config);

            $_SESSION['Office']['miniShop2'] = $this->config;
        }
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('office:minishop2', 'minishop2:default');
    }


    /**
     * @param string $ctx
     *
     * @return bool
     */
    public function initialize($ctx = 'web')
    {
        $this->modx->error->errors = array();
        $this->modx->error->message = '';

        return $this->loadPackage();
    }


    /**
     * @return mixed|null|string
     */
    public function defaultAction()
    {
        if (!$this->modx->user->isAuthenticated($this->modx->context->key)) {
            return $this->modx->user->isAuthenticated('mgr')
                ? $this->modx->lexicon('office_err_mgr_auth')
                : '';
        } else {
            $config = $this->office->makePlaceholders($this->office->config);
            if ($css = trim($this->modx->getOption('office_ms2_frontend_css'))) {
                $this->modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));
            }
            if ($js = trim($this->modx->getOption('office_ms2_frontend_js', null, '[[+jsUrl]]minishop2/default.js'))) {
                $this->office->addClientExtJS();
                $this->office->addClientLexicon(array(
                    'minishop2:default',
                ), 'minishop2/lexicon');
                $this->office->addClientJs(array(
                    $this->office->config['jsUrl'] . 'minishop2/combos.js',
                    $this->office->config['jsUrl'] . 'minishop2/order.window.js',
                    $this->office->config['jsUrl'] . 'minishop2/order_logs.grid.js',
                    $this->office->config['jsUrl'] . 'minishop2/order_products.grid.js',
                    $this->office->config['jsUrl'] . 'minishop2/orders.grid.js',
                    $this->office->config['jsUrl'] . 'minishop2/orders.panel.js',
                    str_replace($config['pl'], $config['vl'], $js),
                ), 'minishop2/all');

                $this->setConfig();
            }

            return $this->office->getChunk($this->config['tplOuter']);
        }
    }


    /**
     * @return bool
     */
    public function loadPackage()
    {
        $corePath = $this->modx->getOption('minishop2.core_path', null,
            $this->modx->getOption('core_path') . 'components/minishop2/');
        $modelPath = $corePath . 'model/';

        return $this->modx->addPackage('minishop2', $modelPath);
    }


    /**
     *
     */
    public function setConfig()
    {
        $grid_fields = array_map('trim', explode(',', $this->modx->getOption('office_ms2_order_grid_fields', null,
            'num,status,cost,weight,delivery,payment,createdon,updatedon', true)));
        $grid_fields = array_unique(array_merge($grid_fields, array('id', 'type', 'actions')));

        $order_fields = array_map('trim', explode(',', $this->modx->getOption('office_ms2_order_form_fields')));
        $address_fields = array_map('trim', explode(',', $this->modx->getOption('office_ms2_order_address_fields')));
        $product_fields = array_map('trim',
            explode(',', $this->modx->getOption('office_ms2_order_product_fields', null, '')));
        $product_fields = array_values(array_unique(array_merge($product_fields, array('product_id', 'name', 'url'))));

        $config = array(
            'ms2_date_format' => (string)str_replace('"', '\"', $this->modx->getOption('office_ms2_date_format')),
            'default_per_page' => $this->modx->getOption('default_per_page'),
            'order_grid_fields' => $grid_fields,
            'order_form_fields' => $order_fields,
            'order_address_fields' => $address_fields,
            'order_product_fields' => $product_fields,
        );
        $reg = '';
        foreach ($config as $k => $v) {
            $reg .= is_array($v)
                ? 'OfficeExt.config.' . $k . '=' . json_encode($v) . ';'
                : 'OfficeExt.config.' . $k . '="' . $v . '";';
        }
        $this->modx->regClientScript('<script type="text/javascript">' . $reg . '</script>', true);
    }


    /**
     * @param $data
     *
     * @return array|string
     */
    public function getOrders($data)
    {
        $data['allowRepeat'] = !empty($this->config['allowRepeat']);
        $data['allowRemove'] = !empty($this->config['allowRemove']);
        /** @var modProcessorResponse $response */
        $response = $this->office->runProcessor('minishop2/orders/getlist', $data);

        return $response->response;
    }


    /**
     * @param $data
     *
     * @return string
     */
    public function getOrder($data)
    {
        /** @var modProcessorResponse $response */
        $response = $this->office->runProcessor('minishop2/orders/get', $data);
        if (!isset($response->response['data'])) {
            $response->response['data'] = array();
        }

        return json_encode($response->response);
    }


    /**
     * @param $data
     *
     * @return string
     */
    public function repeatOrder($data)
    {
        $data['allowRepeat'] = !empty($this->config['allowRepeat']);
        $data['cartLink'] = !empty($this->config['cartLink'])
            ? $this->config['cartLink']
            : '';
        /** @var modProcessorResponse $response */
        $response = $this->office->runProcessor('minishop2/orders/repeat', $data);
        if (!isset($response->response['data'])) {
            $response->response['data'] = array();
        }

        return json_encode($response->response);
    }


    /**
     * @param $data
     *
     * @return string
     */
    public function removeOrder($data)
    {
        $data['allowRemove'] = !empty($this->config['allowRemove']);
        /** @var modProcessorResponse $response */
        $response = $this->office->runProcessor('minishop2/orders/remove', $data);
        if (!isset($response->response['data'])) {
            $response->response['data'] = array();
        }

        return json_encode($response->response);
    }


    /**
     * @param $data
     *
     * @return array|string
     */
    public function getOrderProducts($data)
    {
        /** @var modProcessorResponse $response */
        $response = $this->office->runProcessor('minishop2/orders/product/getlist', $data);

        return $response->response;
    }


    /**
     * @param $data
     *
     * @return array|string
     */
    public function getLog($data)
    {
        /** @var modProcessorResponse $response */
        $response = $this->office->runProcessor('minishop2/orders/getlog', $data);

        return $response->response;
    }


    /**
     * @return array|string
     */
    public function getStatus()
    {
        /** @var modProcessorResponse $response */
        $response = $this->office->runProcessor('minishop2/status/getlist');

        return $response->response;
    }


    /**
     * @param $response
     *
     * @return string
     */
    public function response($response)
    {
        if (!isset($response->response['data'])) {
            $response->response['data'] = array();
        }
        if ($response->response['errors'] === null) {
            $response->response['errors'] = array();
        }
        if ($response->response['message'] === null) {
            $response->response['message'] = '';
        }

        return json_encode($response->response);
    }

}

return 'officeMS2Controller';
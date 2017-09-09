<?php

class msOrderOfficeRepeatProcessor extends modObjectGetProcessor
{
    /** @var msOrder $object */
    public $object;
    public $classKey = 'msOrder';
    public $languageTopics = array('minishop2:default');
    public $permission = '';
    /** @var miniShop2 $ms2 */
    protected $ms2;


    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if (!$this->getProperty('allowRepeat')) {
            return $this->modx->lexicon('access_denied');
        }

        if ($initialize = parent::initialize()) {
            if ($this->object->get('user_id') != $this->modx->user->id) {
                return $this->modx->lexicon('access_denied');
            }
            $this->ms2 = $this->modx->getService('miniShop2');
        }

        return $initialize;
    }


    /**
     * @return array|string
     */
    public function process()
    {
        $this->ms2->initialize();

        $save = $this->ms2->cart->get();
        $this->ms2->cart->clean();

        // Add products to cart
        $products = $this->object->getMany('Products');
        /** @var msOrderProduct $product */
        foreach ($products as $product) {
            $response = $this->ms2->cart->add(
                $product->get('product_id'),
                $product->get('count'),
                $product->get('options')
            );
            if (!$response['success']) {
                $this->ms2->cart->set($save);

                return $this->failure($response['message']);
            }
        }

        // If there is the link to the cart - redirect user
        if ($cartLink = $this->getProperty('cartLink')) {
            if (is_numeric($cartLink)) {
                $cartLink = $this->modx->makeUrl($cartLink, '', '', 'full');
            }

            return $this->success('', array(
                'redirect' => $cartLink,
            ));
        }

        // Otherwise create and submit a new order
        $fields = array('delivery', 'payment');
        foreach ($fields as $field) {
            $response = $this->ms2->order->add($field, $this->object->get($field));
            if (!$response['success']) {
                return $this->failure($response['message']);
            }
        }
        $this->ms2->order->add('email', $this->modx->user->Profile->email);

        if ($address = $this->object->getOne('Address')) {
            $fields = $address->toArray('', true);
            foreach ($fields as $field => $value) {
                if ($field == 'id') {
                    continue;
                }
                $response = $this->ms2->order->add($field, $value);
                if (!$response['success']) {
                    return $this->failure($response['message']);
                }
            }
        }
        $this->ms2->order->config['json_response'] = true;

        return json_decode($this->ms2->order->submit(), true);
    }

}

return 'msOrderOfficeRepeatProcessor';
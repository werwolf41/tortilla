<?php

class msProductOfficeGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'msOrderProduct';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $languageTopics = array('minishop2:product');
    /** @var  miniShop2 $ms2 */
    protected $ms2;


    /**
     * @return bool
     */
    public function initialize()
    {
        $this->ms2 = $this->modx->getService('miniShop2');

        return parent::initialize();
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->innerJoin('msOrder', 'msOrder', '`msOrderProduct`.`order_id` = `msOrder`.`id`');
        $c->leftJoin('msProduct', 'msProduct', '`msOrderProduct`.`product_id` = `msProduct`.`id`');
        $c->leftJoin('msProductData', 'msProductData', '`msOrderProduct`.`product_id` = `msProductData`.`id`');
        $c->where(array(
            'order_id' => $this->getProperty('order_id'),
            'msOrder.user_id' => $this->modx->user->id,
        ));

        $c->select($this->modx->getSelectColumns('msOrderProduct', 'msOrderProduct'));
        $c->select($this->modx->getSelectColumns('msProduct', 'msProduct', 'product_'));
        $c->select($this->modx->getSelectColumns('msProductData', 'msProductData', 'product_', array('id'), true));

        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $fields = array_map('trim', explode(',', $this->modx->getOption('office_ms2_order_product_fields', null, '')));
        $fields = array_values(array_unique(array_merge($fields, array('product_id', 'name', 'url'))));

        $data = array();
        foreach ($fields as $v) {
            $data[$v] = $object->get($v);
            if ($v == 'product_price' || $v == 'product_old_price' || $v == 'price' || $v == 'cost') {
                $data[$v] = $this->ms2->formatPrice($data[$v]);
            } elseif ($v == 'product_weight') {
                $data[$v] = $this->ms2->formatWeight($data[$v]);
            }
        }
        if (empty($data['name'])) {
            $data['name'] = $object->get('product_pagetitle');
        }

        $options = $object->get('options');
        if (!empty($options) && is_array($options)) {
            $tmp = array();
            foreach ($options as $k => $v) {
                $tmp[] = $this->modx->lexicon('office_ms2_' . $k) . ': ' . $v;
                $data['option_' . $k] = $v;
            }
            $data['options'] = implode('; ', $tmp);
        }

        $data['url'] = $object->get('product_id')
            ? $this->modx->makeUrl($object->get('product_id'), $object->get('product_context_key'), '', 'full')
            : '';

        return $data;
    }

}

return 'msProductOfficeGetListProcessor';
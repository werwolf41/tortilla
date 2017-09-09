<?php

class msOrderOfficeGetProcessor extends modObjectGetProcessor
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
    public function cleanup()
    {
        $order_fields = array_map('trim',
            explode(',', $this->modx->getOption('office_ms2_order_form_fields', null, '', true))
        );
        $order_fields = array_intersect($order_fields, array_keys($this->modx->getFieldMeta('msOrder')));
        if (!in_array('cost', $order_fields)) {
            $order_fields[] = 'cost';
        }
        unset($order_fields['comment']);

        $array = array();
        foreach ($order_fields as $v) {
            if ($v == 'createdon' || $v == 'updatedon') {
                $array[$v] = $this->ms2->formatDate($this->object->get($v));
            } elseif ($v == 'cost' || $v == 'cart_cost' || $v == 'delivery_cost') {
                $array[$v] = $this->ms2->formatPrice($this->object->get($v));
            } elseif ($v == 'weight') {
                $array[$v] = $this->ms2->formatWeight($this->object->get($v));
            } else {
                $array[$v] = $this->object->get($v);
            }
        }

        if ($profile = $this->object->getOne('UserProfile')) {
            $array = array_merge($array, array('fullname' => $profile->get('fullname')));
        }

        if (in_array('status', $order_fields) && $tmp = $this->object->getOne('Status')) {
            $array['status'] = $this->modx->lexicon(str_replace(array('[[%', ']]'), '', $tmp->get('name')));
        }
        if (in_array('delivery', $order_fields) && $tmp = $this->object->getOne('Delivery')) {
            $array['delivery'] = $this->modx->lexicon(str_replace(array('[[%', ']]'), '', $tmp->get('name')));
        }
        if (in_array('payment', $order_fields) && $tmp = $this->object->getOne('Payment')) {
            $array['payment'] = $this->modx->lexicon(str_replace(array('[[%', ']]'), '', $tmp->get('name')));
        }

        $address_fields = array_map('trim',
            explode(',', $this->modx->getOption('office_ms2_order_address_fields', null, '', true)));
        $address_fields = array_intersect($address_fields, array_keys($this->modx->getFieldMeta('msOrderAddress')));
        /** @var msOrderAddress $address */
        if ($address = $this->object->getOne('Address')) {
            foreach ($address_fields as $v) {
                $array['addr_' . $v] = $address->get($v);
            }
        }

        $array = array_map('strip_tags', $array);

        return $this->success('', $array);
    }


    /**
     * @param string $date
     *
     * @return string
     */
    public function formatDate($date = '')
    {
        $df = $this->modx->getOption('office_ms2_date_format', null, '%d.%m.%Y %H:%M');

        return (!empty($date) && $date !== '0000-00-00 00:00:00') ? strftime($df, strtotime($date)) : '&nbsp;';
    }
}

return 'msOrderOfficeGetProcessor';
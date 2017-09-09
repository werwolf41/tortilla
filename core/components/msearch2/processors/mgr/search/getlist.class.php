<?php

class mseIndexGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'modResource';
    public $classKey = 'modResource';
    /** @var mSearch2 $mSearch2 */
    public $mSearch2;
    protected $ids = array();


    /**
     * @return bool|null|string
     */
    public function beforeQuery()
    {
        return $this->loadClass();
    }


    /**
     * @return array
     */
    public function getData()
    {
        $data = array();
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));

        if ($query = $this->getProperty('query')) {
            $minQuery = $this->modx->getOption('index_min_words_length', null, 3, true);
            if (preg_match('/^[0-9]{2,}$/', $query) || mb_strlen($query, 'UTF-8') >= $minQuery) {
                $this->ids = $this->mSearch2->Search($query);
            }
        }
        if (empty($this->ids)) {
            return array('total' => 0, 'results' => array());
        }

        /* query for chunks */
        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        $data['total'] = $this->modx->getCount($this->classKey, $c);
        $c = $this->prepareQueryAfterCount($c);

        $ids = array_keys($this->ids);
        $c->sortby('find_in_set(`id`,\'' . implode(',', $ids) . '\')', '');
        if ($limit > 0) {
            $c->limit($limit, $start);
        }

        $c->select(array(
            $this->modx->getSelectColumns($this->classKey, $this->classKey),
            $this->modx->getSelectColumns('mseIntro', 'mseIntro', '', array('intro')),
        ));

        if ($c->prepare() && $c->stmt->execute()) {
            $data['results'] = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $data;
    }


    /**
     * @param array $data
     *
     * @return array
     */
    public function iterate(array $data)
    {
        $list = array();
        foreach ($data['results'] as $array) {
            $objectArray = $this->prepareArray($array);
            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray;
            }
        }

        return $list;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->where(array('id:IN' => array_keys($this->ids)));
        $c->leftJoin('mseIntro', 'mseIntro', '`modResource`.`id` = `mseIntro`.`resource`');

        if (!$this->getProperty('unpublished')) {
            $c->where(array('published' => 1));
        }
        if (!$this->getProperty('deleted')) {
            $c->where(array('deleted' => 0));
        }

        return $c;
    }


    /**
     * @param array $array
     *
     * @return array
     */
    public function prepareArray(array $array)
    {
        $array['weight'] = $this->ids[$array['id']];
        $array['intro'] = $this->mSearch2->Highlight($array['intro'], $this->getProperty('query'), '<b>', '</b>');

        return $array;
    }


    /**
     * @return bool
     */
    public function loadClass()
    {
        if ($this->modx->loadClass('msearch2', MODX_CORE_PATH . 'components/msearch2/model/msearch2/', false, true)) {
            $this->mSearch2 = new mSearch2($this->modx, array());
        }

        return $this->mSearch2 instanceof mSearch2;
    }

}

return 'mseIndexGetListProcessor';
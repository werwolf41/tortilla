<?php

/**
* The base class for piecesMap
*/
class piecesMap {
/* @var modX $modx */
    public $modx;

    private $map = array();
    private $mapsLoaded = false;

    function __construct(modX &$modx) {
        $this->modx =& $modx;
    }

    private function loadMap() {
        $paramId2Name = array();

        $q = $this->modx->newQuery('sfParam');
        $q->select($this->modx->getSelectColumns('sfParam', 'sfParam'));
        if ($q->prepare() && $q->stmt->execute()) {
            $rows = $q->stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($rows as $row) {
                $this->map[$row['name']] = array(
                    'id' => $row['id'],
                    'pieces' => array(),
                );
                $paramId2Name[$row['id']] = $row['name'];
            }
        }

        $q = $this->modx->newQuery('sfPiece');
        $q->select($this->modx->getSelectColumns('sfPiece', 'sfPiece'));

        if ($q->prepare() && $q->stmt->execute()) {
            $rows = $q->stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($rows as $row) {
                if(!empty($row['alias'])) {
                    $this->map[$paramId2Name[$row['param']]]['pieces'][$row['alias']] = array(
                        'id' => $row['id'],
                        'param_id' => $row['param'],
                        'param' => $paramId2Name[$row['param']],
                        'value' => $row['value'],
                        'alias' => $row['alias'],
                        'correction' => $row['correction'],
                        'title' => $this->buildPieceTitle($row['value'], $row['correction']),
                    );
                }
            }
        }

        $this->mapsLoaded = true;
    }

    private function buildPieceTitle($value, $correction) {
        if(!empty($correction)) {
            return $correction;
        }

        if(empty($value)) {
            return '';
        }

        $first = mb_substr($value, 0, 1, 'UTF-8');
        $last = mb_substr($value, 1, null, 'UTF-8');
        $first = mb_strtolower($first, 'UTF-8');

        return $first.$last;
    }

    public function findAlias($param, $value) {
        if(!$this->mapsLoaded) {
            $this->loadMap();
        }

        if(array_key_exists($param, $this->map)) {
            foreach($this->map[$param]['pieces'] as $piece){
                if($piece['value'] == $value ) {
                    return $piece['alias'];
                }
            }

        }
        return '';
    }

    public function findDeepAlias($categoryFilters, $pieces, $alias, $param) {
        $result = array();
        foreach($categoryFilters as $currentFilter) {
            if(array_key_exists($currentFilter, $this->map)) {
                if($param == $currentFilter) {
                    $result[] = $alias;
                }
                else{
                    foreach($this->map[$currentFilter]['pieces'] as $k => $v) {
                        if(in_array($k, $pieces)) {
                            $result[] = $k;
                            break;
                        }
                    }
                }
            }
        }

        if(count($result) == count($pieces) + 1) {
            return $result;
        }

        return null;
    }

    public function findPieceData($alias){
        if(empty($alias)) {
            return null;
        }

        if(!$this->mapsLoaded) {
            $this->loadMap();
        }

        foreach($this->map as $param => $paramData) {
            if(array_key_exists($alias, $paramData['pieces'])) {
                return $paramData['pieces'][$alias];
            }
        }

        return null;
    }
}
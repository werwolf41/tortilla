<?php

/**
 * Generate an sfPiece
 */
class seoFilterPiecesGenerateProcessor extends modProcessor {
    public $objectType = 'sfPiece';
    public $classKey = 'sfPiece';
    public $languageTopics = array('seofilter');

    /* @var $param sfParam */
    private $param;

    /**
     * Run the processor and return the result. Override this in your derivative class to provide custom functionality.
     * Used here for pre-2.2-style processors.
     *
     * @return mixed
     */
    public function process()
    {
        $paramId = $this->getProperty('param');
        if (empty($paramId) || !$this->param = $this->modx->getObject('sfParam', $paramId)) {
            return $this->failure($this->modx->lexicon('seofilter_request_error'));
        }

        $paramType = $this->param->get('type');

        $pieces = null;
        switch($paramType) {
            case 'vendor':
                $pieces = $this->generateVendorPieces();
                break;
            case 'field':
                $pieces = $this->generateFieldPieces();
                break;
            case 'field_json':
                $pieces = $this->generateFieldJsonPieces();
                break;
            default:
                return $this->failure($this->modx->lexicon('seofilter_param_type_not_supported'));
        }
        if(!empty($pieces)) {
            $this->param->addMany($pieces, 'Pieces');
            $this->param->save();
        }

        return $this->success($paramId);
    }

    private function  generateVendorPieces()
    {
        $vendors = $this->modx->getIterator('msVendor');
        $pieces = array();
        foreach($vendors as $idx => $vendor) {
            $piece = $this->modx->getObject('sfPiece', array('param' => $this->param->get('id'), 'value' => $vendor->get('id')));
            if(!$piece) {
                $pieces[] = $this->modx->newObject('sfPiece', array(
                    'value' => $vendor->get('id'),
                    'correction' => $vendor->get('name'),
                ));
            }
        }
        return $pieces;
    }

    private function generateFieldPieces() {
        $pieces = array();
        $paramName = $this->param->get('name');

        $q = $this->modx->newQuery('msProductData');
        $q->select('DISTINCT('.$paramName.')');
        if ($q->prepare() && $q->stmt->execute()) {
            $values = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($values as $value) {
                $v = $value[$paramName];
                if(!empty($v)) {
                    $piece = $this->modx->getObject('sfPiece', array('param' => $this->param->get('id'), 'value' => $v));
                    if(!$piece) {
                        $pieces[] = $this->modx->newObject('sfPiece', array(
                            'value' => $v,
                        ));
                    }
                }
            }
        }
        return $pieces;
    }

    private function generateFieldJsonPieces() {
        $pieces = array();
        $paramName = $this->param->get('name');

        $q = $this->modx->newQuery('msProductOption');
        $q->select('DISTINCT(value)');
        $q->where(array('key' => $paramName));

        if ($q->prepare() && $q->stmt->execute()) {
            $values = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($values as $value) {
                $v = $value['value'];
                if(!empty($v)) {
                    $piece = $this->modx->getObject('sfPiece', array('param' => $this->param->get('id'), 'value' => $v));
                    if(!$piece) {
                        $pieces[] = $this->modx->newObject('sfPiece', array(
                            'value' => $v,
                        ));
                    }
                }
            }
        }

        return $pieces;
    }

}

return 'seoFilterPiecesGenerateProcessor';
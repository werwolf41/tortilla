<?php

/**
 * Some action with multiple ecMessages
 */
class easyCommMessageMultipleProcessor extends modProcessor
{
    /**
     * @return array|string
     */
    public function process()
    {
        if (!$actionMethod = $this->getProperty('actionMethod', false)) {
            return $this->failure();
        }
        $ids = json_decode($this->getProperty('ids'), true);
        if (empty($ids)) {
            return $this->success();
        }
        /** @var easyComm $easyComm */
        $easyComm = $this->modx->getService('easyComm');
        foreach ($ids as $id) {
            /** @var modProcessorResponse $response */
            $response = $easyComm->runProcessor('mgr/message/' . $actionMethod, array('id' => $id));
            if ($response->isError()) {
                return $response->getResponse();
            }
        }
        return $this->success();
    }
}
return 'easyCommMessageMultipleProcessor';
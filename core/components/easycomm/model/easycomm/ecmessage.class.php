<?php
class ecMessage extends xPDOSimpleObject {

    public function notifyUser(){
        if(!$this->get('notify')){
            return false;
        }
        /* @var easyComm $easyComm*/
        $easyComm = $this->xpdo->getService('easyComm','easyComm',$this->xpdo->getOption('ec_core_path',null,$this->xpdo->getOption('core_path').'components/easycomm/').'model/easycomm/');
        if (!($easyComm instanceof easyComm)) {
            return false;
        }
        return $easyComm->sendUpdateMessageNotification($this->toArray());
    }
}
<?php
/**
 * 
 */
namespace App\Modules\Finance\Entity;

class CashierEntity
{
    public $batchId;
    public $reqCode;

    public function setBatchId($batchId){
        $this->batchId = $batchId;
    }

    public function getBatchId(){
        return $this->batchId;
    }

    public function setReqCode($reqCode){
        $this->reqCode = $reqCode;
    }

    public function getReqCode(){
        return $this->reqCode;
    }

}
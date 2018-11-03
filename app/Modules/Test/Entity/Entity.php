<?php
/**
 * 
 */
namespace App\Modules\Test\Entity;

class Entity
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
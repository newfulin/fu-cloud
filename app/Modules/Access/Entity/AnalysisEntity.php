<?php
/**
 * 报表实体
 */
namespace App\Modules\Access\Entity;
/**
 * 报表实体
 */
class AnalysisEntity
{
    public $Id;//关联Id
    public $IP;//客户端IP
    public $Type;//分类
    public $UserId;//用户ID
    public $Name;//统计主题
    public $Desc;//统计描述(如果需要)
    public $Remark;//备注(区分获取分享信息与分享)
    public $OpenId;//微信用户唯一标识(统计查看次数需要)

    public function setId($Id){
        $this->Id = $Id;
    }

    public function getId(){
        return $this->Id;
    }

    public function setIP($IP){
        $this->IP = $IP;
    }

    public function getIP(){
        return $this->IP;
    }

    public function setType($Type){
        $this->Type = $Type;
    }

    public function getType(){
        return $this->Type;
    }

    public function setUserId($UserId){
        $this->UserId = $UserId;
    }

    public function getUserId(){
        return $this->UserId;
    }

    public function setName($Name){
        $this->Name = $Name;
    }

    public function getName(){
        return $this->Name;
    }

    public function setDesc($Desc){
        $this->Desc = $Desc;
    }

    public function getDesc(){
        return $this->Desc;
    }
    public function setRemark($Remark){
        $this->Remark = $Remark;
    }

    public function getRemark(){
        return $this->Remark;
    }
    public function setOpenId($OpenId){
        $this->OpenId = $OpenId;
    }

    public function getOpenId(){
        return $this->OpenId;
    }
}
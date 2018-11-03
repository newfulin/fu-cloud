<?php

namespace App\Modules\Access\Repository;


use App\Common\Contracts\Repository;
use App\Common\Models\CommFeedback;

class CommFeedbackRepo extends Repository
{
    public function __construct(CommFeedback $model)
    {
        $this->model = $model;
    }

    // 提交反馈
    public function createFeedback($params)
    {
        $time = time();
        $dateTime = date('Y-m-d H:i:s', $time);
        $this->model->id = ID();
        $this->model->user_id = $params['userId'];

        $this->model->create_by = $params['userId'];
        $this->model->create_time = $dateTime;
        $this->model->update_by = $params['userId'];
        $this->model->update_time = $dateTime;

        $this->model->basic_info = $params['basicInfo'];
        $this->model->content = $params['content'];
        $this->model->status = '10';

        $ret = $this->model->save();
        if (!$ret) {
            Err('提交失败，稍后请重试', '7777');
        }
        return $ret;
    }


}
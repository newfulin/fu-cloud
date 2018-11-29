<?php
namespace App\Modules\Access\Service;

use App\Common\Contracts\Service;
use App\Modules\Access\Repository\CommFeedbackRepo;
use App\Modules\Transaction\Repository\CommUserInfoRepository;

class GeneralSer extends Service
{
    public function getRules()
    {

    }
    protected $user;
    protected $feed;
    public function __construct(CommUserInfoRepository $user, CommFeedbackRepo $feed)
    {
        $this->user = $user;
        $this->feed = $feed;
    }
    // 反馈
    public function feedback($request)
    {
        $this->feed->createFeedback($request);
        return '0000';
    }
}
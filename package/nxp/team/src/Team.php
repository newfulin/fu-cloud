<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/5/16
 * Time: 18:41
 */

namespace Nxp\Team;


class Team {
    //创建
    public function create()
    {
        return app()->make(Create::class);
    }

    //查询
    public function query()
    {
        return app()->make(Query::class);
    }
}
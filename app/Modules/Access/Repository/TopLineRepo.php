<?php
/**
 * Created by PhpStorm.
 * User: wqb
 * Date: 2018/3/1
 * Time: 13:39
 */
namespace App\Modules\Access\Repository;

use App\Common\Contracts\Repository;

use App\Common\Models\TopLine;
use Illuminate\Support\Facades\DB;
use QL\QueryList;

class TopLineRepo extends Repository{
    public function __construct(TopLine $model)
    {
        $this->model = $model;
    }
    public function checkJokes($createBy)
    {
        $ret = optional($this->model
            ->select('id')
            ->where('create_by',$createBy)
            ->first())
            ->toArray();
        return $ret;
    }

    public function getTopList($request)
    {
        if(!$request['type']){
            $request['type'] = '10';
        }
        $ret =  optional(DB::table('erp_top_line as t0')
            ->select('t0.id','t0.title','t0.author','t0.total_num','t0.top_type','t0.show_type','t0.attr1','t0.attr2','t0.attr3')
            ->where('top_type',$request['type'])
            ->where('top_status',1)
            ->orderBy('update_time','desc')
            ->paginate($request['pageSize']))
            ->toArray();
        $ret = $ret['data'];
        foreach($ret as $key => $val){
            $ret[$key]->attr1 =  R($val->attr1,false);
            $ret[$key]->attr2 =  R($val->attr2,false);
            $ret[$key]->attr3 =  R($val->attr3,false);
            $ret[$key]->click_count = $this->getClickCount($val->id,$request['user_id']);
            }
        return $ret;
}

    public function getClickCount($id,$userId)
    {
        $count = DB::table('click_count as t0')
            ->select('id')
            ->where('status','10')
            ->where('obj_id',$id)
            ->where('user_id',$userId)
            ->get();
        $ret = count($count);
        return $ret;
        }


    
    public function getRecoList($request)
    {
        $ret =  optional(DB::table('erp_top_line as t0')
            ->select('t0.id','t0.title','t0.author','t0.total_num','t0.day_status','t0.top_type','t0.show_type','t0.attr1','t0.attr2','t0.attr3','t0.con_site')
            ->where('status',$request['status'])
            ->where('top_status',1)
            ->orderBy('update_time','desc')
            ->paginate($request['pageSize']))
            ->toArray();
        $ret = $ret['data'];
        foreach($ret as $key => $val){
            $ret[$key]->attr1 =  R($val->attr1,false);
            $ret[$key]->attr2 =  R($val->attr2,false);
            $ret[$key]->attr3 =  R($val->attr3,false);
        }


            return $ret;
}


    public function getIndexTop($request)
    {
        $ret =  optional(DB::table('erp_top_line as t0')
            ->select('t0.id','t0.title','t0.author','t0.total_num','t0.top_type','t0.show_type','t0.attr1','t0.attr2','t0.attr3')
            ->where('top_type',$request['type'])
            ->where('top_status',1)
            ->orderBy('update_time','desc')
            ->limit(8))
            ->toArray();
        $ret = $ret['data'];
        foreach($ret as $key => $val) {
            $ret[$key]->attr1 = R($val->attr1, false);
            $ret[$key]->attr2 = R($val->attr2, false);
            $ret[$key]->attr3 = R($val->attr3, false);
        }
    }
    public function getTopInfo($request)
    {

        $ret =  optional(DB::table('erp_top_line as t0')
            ->select('*')
            ->where('id',$request['id'])
            ->get())
            ->toArray();
        if (!$ret) {
            Err('请求错误');
        }
        $num = $ret[0]->total_num+1;
        DB::table('erp_top_line')
            ->where('id',$request['id'])
            ->update([
                'total_num' =>$num,
            ]);
        $ret[0]->attr1 =  R($ret[0]->attr1,false);
        $ret[0]->content = $this->makeJsContent($ret[0]->content);
        $ret[0]->click_count = $this->getClickCount($ret[0]->id,$request['user_id']);
        return $ret;
    }

    public function getHomeTopList()
    {
        $ret = optional($this->model
            ->select('id','title','author','total_num','top_type','show_type','attr1','attr2','attr3')
            ->paginate(8))->toArray();
        return $ret['data'];
    }

    //获取头条分享数据
    public function getShareInfo($request){
        return optional($this->model
            ->select('id','title','top_desc','attr1')
            ->where('id',$request['id'])
            ->first())
            ->toArray();
    }

    public function makeJsContent($html)
    {
        $rules = [
            'text' =>['span','text'],
            'text_style' =>['span','style'],
            'img' =>['img','src'],
            'img_w' =>['img','width'],
            'img_h' =>['img','height'],

        ];

        $data =  QueryList::html($html)
            ->rules($rules)
            ->range('p')
            ->query()
            ->getData(function ($item){
                if($item['img']) {
                    return [
                        'name' => 'ViewImg',
                        'src' => $item['img'],
                        'height' => $item['img_h'],
                        'width' => $item['img_w']
                    ];
                }elseif ($item['text']){
                    $item['name'] = 'ViewText';
                    foreach (explode(';',$item['text_style']) as $style){
                        $exp = explode(':',$style);
                        if(count($exp)>1){
                            list($k,$v) = $exp;
                            $item['style'][trim(str_replace('-','_',$k))]=$v;
                        }

                    }

                    return [
                        'name' =>'ViewText',
                        'content' =>$item['text'],
                        'style' =>$item['style']
                    ];
                }
                else{
                    return [
                    ];
                }

            });
        return $data->all();

    }
}
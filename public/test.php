<?php
header('Access-Control-Allow-Origin:*');
$response = [
    '1',
    '2',
    '3'
];
$ret = [
    'ret' =>200,
    'data'=>$response,
    'code' =>'0000',
    'message' =>'请求成功'
];
//$json = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

echo json_encode((object)$ret);
//return json_decode($json);

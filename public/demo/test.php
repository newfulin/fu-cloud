<?php

class User {

    public function __get($name){
        return 'OK_'.$name;
    }



}



$user = new User();
echo $user->tran_trans_order;
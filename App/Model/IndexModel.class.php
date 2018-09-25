<?php

namespace App\Model;

class IndexModel extends \Core\Model {


    private $table = 'admin';
    /**
     * 获取数据列表
    */
    public function getList(){

        return $this->select($this->table,'*',[
            "LIMIT"=>5,
            "ORDER" => ["id" => "DESC"],
        ]);
    }

}
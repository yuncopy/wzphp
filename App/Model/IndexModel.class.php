<?php

namespace App\Model;

use WzPHP\Core\Model;

class IndexModel extends Model {


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
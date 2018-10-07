<?php


/**
 *
 * 测试接口
 *
*/
namespace App\Api\Controller;

use App\Model\IndexModel;
use WzPHP\Core\Log as Log;


class StudentController extends BaseController
{


    /**
     *
     * 获取信息
     * @author chenhuian
    */
    public function indexAction()
    {

        echo 'index';
        Log::write(__FUNCTION__);

        (new IndexModel)->listData();
    }

    /**
     *
     * 添加数据
     *
    */
    public function addAction()
    {
        echo 'add';
        Log::write(__FUNCTION__);
    }

    /**
     * 编辑数据
    */
    public function editAction()
    {

        self::JSON(3002,['data'=>'333']);
    }


    /**
     * 保存数据
    */
    public function saveAction(){
        
    }
}

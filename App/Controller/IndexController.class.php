<?php
namespace App\Controller;

use App\Model\IndexModel;
use Core\Log;
class IndexController
{
    public function indexAction()
    {


        $data = (new IndexModel)->getList();
        //$a = 0/2;
        //var_dump($ee);
        var_dump($data);
    }
}

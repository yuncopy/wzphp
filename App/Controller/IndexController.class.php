<?php
namespace App\Controller;

use App\Model\IndexModel;
use Core\Log;
class IndexController
{
    public function indexAction()
    {


        $data = (new IndexModel)->getList();

        var_dump($data);
    }
}

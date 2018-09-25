<?php
namespace App\Controller;

use App\Model\IndexModel;
use Core\Log;
class IndexController
{
    public function indexAction()
    {

        echo 'index';
        Log::write();

        (new IndexModel)->listData();
    }
}

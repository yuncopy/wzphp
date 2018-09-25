<?php
namespace App\Api\Controller;

use App\Model\IndexModel;
use Core\Log;
class StudentController
{
    public function indexAction()
    {

        echo 'index';
        Log::write();

        (new IndexModel)->listData();
    }
}

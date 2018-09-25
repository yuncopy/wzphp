<?php
namespace App\Controller;

use App\Model\IndexModel;
use Core\Log;
class StudentController
{
    public function indexAction()
    {

        echo 'student';
        Log::write();

        (new IndexModel)->listData();
    }
}

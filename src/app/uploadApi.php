<?php
namespace app;

use epii\admin\center\admin_center_controller;
use epii\server\Args;
use wslibs\storage_php_sdk\StorageManager;

class uploadApi extends admin_center_controller
{

    public function upload()
    {
        echo StorageManager::storage_task_creat();
        exit;
    }
}
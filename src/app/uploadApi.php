<?php
namespace app;

use epii\admin\center\admin_center_controller;
use epii\server\Args;
use StorageManager;

class uploadApi extends admin_center_controller
{
    public function upload()
    {
        $site = trim(Args::params("site"));
        if(empty($site)) $site = null;
        $overtime = intval(Args::params("overtime"));
        if(empty($overtime)) $overtime = null;
        echo StorageManager::storage_task_creat($site, $overtime);
        exit;
    }
}
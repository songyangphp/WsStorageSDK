<?php

namespace wslibs\storage_php_sdk;

use epii\admin\ui\EpiiAdminUi;

class StorageManager
{
    private static $app_id;

    private static $secret;

    private static $upload_url = "http://file.wszx.cc/index.php/storage/index/token/";

    private static $check_url = "http://center.wenshi.wszx.cc/?app=storage_config@checkSignAndGetConfig";

    private static $get_dir_url = "http://file.wszx.cc/index.php/storage/get_cloud_root_dir";

    private static $creat_task_ajax_url = "?app=uploadApi@upload&_upload_yun=1";

    public static $over_time = 180; //token默认过期时间

    public static $site = null; //自定义存储位置 注意去除字符串两边的 "/"

    public static function storage_init($app_id, $secret, $creat_task_ajax_url = null ,$check_url = null, $upload_url = null, $get_dir_url = null)
    {
        self::$app_id = $app_id;
        self::$secret = $secret;
        if(!is_null($check_url)) self::$check_url = $check_url;
        if(!is_null($upload_url)) self::$upload_url = $upload_url;
        if(!is_null($get_dir_url)) self::$get_dir_url = $get_dir_url;
        if(!is_null($creat_task_ajax_url)) self::$creat_task_ajax_url = $creat_task_ajax_url;

        EpiiAdminUi::addPluginData("ws_upload_yun_get_token",self::$creat_task_ajax_url);
        EpiiAdminUi::addPluginData("ws_upload_yun_api",self::$upload_url);
    }

    public static function storage_task_creat()
    {
        $url = self::$check_url;
        $time = time();
        $post_data = [
            '_time' => $time,
            '_token' => md5($time . self::$secret),
            'app_id' => self::$app_id,
            'overtime' =>  self::$over_time,
            'site' => self::$site
        ];

        return self::curl_post($url,$post_data);
    }

    public static function get_cloud_root_dir($show_dir = true)
    {
        $url = self::$get_dir_url;
        $post_data = [
            'app_id' => self::$app_id
        ];

        $dir_data = self::curl_post($url, $post_data);
        if($show_dir === true){
            return json_decode($dir_data,true)['data']['dir'];
        }else{
            return $dir_data;
        }
    }

    private static function curl_post($url, $post_data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
<?php

namespace wslibs\storage_php_sdk;

use epii\admin\ui\EpiiAdminUi;

class StorageManager
{
    public static $app_id;

    public static $secret;

    public static $upload_url = "http://file.wszx.cc/index.php/storage/index";

    public static $check_url = "http://center.wenshi.wszx.cc/?app=storage_config@checkSignAndGetConfig";

    public static $over_time = 180; //token默认过期时间

    public static function storage_init($app_id, $secret, $check_url = null, $upload_url = null)
    {
        self::$app_id = $app_id;
        self::$secret = $secret;
        if(!is_null($check_url)){
            self::$check_url = $check_url;
        }
        if(!is_null($upload_url)){
            self::$upload_url = $check_url;
        }

        EpiiAdminUi::addPluginData("ws_upload_yun_get_token","?app=uploadApi@upload&_upload_yun=1");
        EpiiAdminUi::addPluginData("ws_upload_yun_api",$upload_url);
    }

    public static function storage_task_creat($site = null, $over_time = null)
    {
        $url = self::$check_url;
        $time = time();
        $post_data = [
            '_time' => $time,
            '_token' => md5($time . self::$secret),
            'app_id' => self::$app_id,
            'overtime' => self::$over_time
        ];

        if(!is_null($site)){
            $post_data['site'] = $site;
        }
        return self::curl_post($url,$post_data);
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
<?php


class StorageManager
{
    public static $app_id;

    public static $secret;

    public static $engine;

    public static $check_url = "http://192.168.16.19/WWW2/pdfcenter/public/index.php?app=storage_config@checkSignAndGetConfig";

    public static function storage_init($app_id, $secret, $check_url = null)
    {
        self::$app_id = $app_id;
        self::$secret = $secret;
        if(!is_null($check_url)){
            self::$check_url = $check_url;
        }
    }

    public static function storage_task_creat($site = null)
    {
        $url = self::$check_url;
        $time = time();
        $post_data = [
            '_time' => $time,
            '_token' => md5($time . self::$secret),
            'app_id' => self::$app_id,
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
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
<?php

class Url{
    public static $domain = 'wcpt.exesfull.com';
    public static $path = 'https://wcpt.exesfull.com';
    public static $favicon = 'https://wcpt.exesfull.com/favicon.ico';
    public static $url_p_start = 0;
    
    public static function now_path(){
        $url = $_SERVER['REQUEST_URI'];
        return explode('?', $url)[0];
    }

    public static function now_url(){
        return ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /*public static function MakeUrl($path){
        return 'https://'.$this->domain.$path;
    }*/
}

?>
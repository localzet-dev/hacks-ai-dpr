<?php
//include_once 'class/class.php';
include_once 'class/url.php';
include_once 'class/dauth.php';
include_once 'class/upload.php';
include_once 'class/db.php';
//include_once 'class/debug.php';

class Defs{
    // Сбор отдельных функций из E.S.M.

    public static function time(){
        return date('Y-m-d H:i:s');
    }

    public static function gen_password_int($length = 20){
        $chars = '0123456789';
        $size = strlen($chars) - 1;
        $password = '';
        while($length--) {
            $password .= $chars[random_int(0, $size)];
        }
        return $password;
    }
    
    public static function gen_password_str($length = 20){
        $chars = '0123456789QWERTYUIOPLKJHGFDSAZXCVBNMqwertyuioplkjhgfdsazxcvbnm';
        $size = strlen($chars) - 1;
        $password = '';
        while($length--) {
            $password .= $chars[random_int(0, $size)];
        }
        return $password;
    }
        
    public static function gen_password_low_str($length = 20){
        $chars = '0123456789qwertyuioplkjhgfdsazxcvbnm';
        $size = strlen($chars) - 1;
        $password = '';
        while($length--) {
            $password .= $chars[random_int(0, $size)];
        }
        return $password;
    }

    public static function dormitory_get_room_titles(){
        $sql = "SELECT id, title FROM dormitory_rooms_title";
        $arr = DB::query($sql);
        $rooms = array();
        foreach($arr as $key => $item){
            $rooms[$item['id']] = $item['title'];
        } 
        return $rooms;
    }

}
?>
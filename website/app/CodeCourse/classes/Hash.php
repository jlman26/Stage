<?php 

class Hash{

    public static function make($string, $salt = ''){
        return hash('sha256', $string . $salt);
    }
    public static function salt($length)
    {
        $bytes = openssl_random_pseudo_bytes($length, $strong);
        return bin2hex($bytes);
    }
    public static function unique(){
        return self::make(uniqid());
    }

}

?>
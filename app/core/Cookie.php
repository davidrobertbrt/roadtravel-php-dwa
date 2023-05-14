<?php
final class Cookie{
    public static function set($name,$value,$expiry){
        $expirationTime = time() + $expiry;
        setcookie($name,$value,$expirationTime,"/");
    }

    public static function get($name){
        return $_COOKIE[$name] ?? null;
    }

    public static function delete($name){
        setcookie($name,null,-1,"/");
        unset($_COOKIE[$name]);
    }
}
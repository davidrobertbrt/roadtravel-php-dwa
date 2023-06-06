<?php

final class ProtocolConfig{
    public static function getProtocol() {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    } 
}
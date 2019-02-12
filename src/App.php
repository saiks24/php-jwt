<?php
namespace Saiks24\JWT;

class App
{
    public static function getConfig() : array
    {
        $config = include __DIR__.'/../configs/config.php';
        return $config;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 13.02.19
 * Time: 12:58
 */

namespace Saiks24\JWT\Storage;


use Saiks24\JWT\JWT;

class RedisStorage implements Persisted
{
    /** @var \Redis */
    private $redisConnect;
    /**
     * RedisStorage constructor.
     */
    public function __construct()
    {
        $redisConnect = new \Redis();
        $redisConnect->connect('0.0.0.0');
        $this->redisConnect = $redisConnect;
    }

    public function get($id): JWT
    {
        $jwtString = $this->redisConnect->hGet('jwt:usersjwt',$id);
        $jwt = JWT::create($jwtString);
        return $jwt;
    }

    public function add($id , JWT $jwt): bool
    {
        return (bool)$this->redisConnect->hSet('jwt:usersjwt',$id,$jwt->__toString());
    }

    public function remove($id): bool
    {
        return (bool)$this->redisConnect->hDel('jwt:usersjwt',$id);
    }

}
<?php

namespace Saiks24\JWT\Storage;


use Saiks24\JWT\JWT;

class RedisStorage implements Persisted
{
    /** @var \Redis */
    private $redisConnect;

    /**
     * RedisStorage constructor.
     *
     * @param array $config
     * [
     *      'address' => 'address of redis server'
     *      'port' => 'port of redis server'
     *      'user' => 'user for redis server'
     *      'password' => 'password to login'
     * ]
     */
    public function __construct(array $config)
    {
        $redisConnect = new \Redis();
        $redisConnect->connect($config['address']);
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
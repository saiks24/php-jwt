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

    public function get($id): JWT
    {
        // TODO: Implement get() method.
    }

    public function add(JWT $jwt): bool
    {
        // TODO: Implement add() method.
    }

    public function remove(JWT $jwt): bool
    {
        // TODO: Implement remove() method.
    }

}
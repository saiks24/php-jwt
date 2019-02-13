<?php
namespace Saiks24\JWT\Storage;

use Saiks24\JWT\JWT;

interface Persisted
{
    public function get($id) : JWT;
    public function add($id , JWT $jwt) : bool;
    public function remove($id) : bool;
}
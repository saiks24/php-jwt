<?php
namespace Saiks24\JWT\Storage;

use Saiks24\JWT\JWT;

/** Storage contract
 * Interface Persisted
 *
 * @package Saiks24\JWT\Storage
 */
interface Persisted
{

    /** Add info to persistent storage
     * @param $id
     *
     * @return \Saiks24\JWT\JWT
     */
    public function get($id) : JWT;

    /** Add to id jwt key
     * @param                  $id
     * @param \Saiks24\JWT\JWT $jwt
     *
     * @return bool
     */
    public function add($id , JWT $jwt) : bool;

    /** Remove key from persistent storage
     * @param $id
     *
     * @return bool
     */
    public function remove($id) : bool;
}
<?php
namespace Saiks24\JWT;

use Saiks24\JWT\Exceptions\JWTWrongFormat;
use Saiks24\JWT\Exceptions\MissingConfiguration;
use Saiks24\JWT\Storage\Persisted;
// TODO Распилить на отдельные классы для access и refresh токенов
class JWT implements Persisted
{
    /** @var array  */
    private $header;
    /** @var array  */
    private $payload;
    /** @var string  */
    private $signature;
    /** @var boolean  */
    private $isValid;
    /** @var Persisted */
    public $storage;

    /**
     * JWT constructor.
     * @param string $header
     * @param string $payload
     * @param string $signature
     * @param bool $isValid
     */
    public function __construct(string $header, string $payload, string $signature, bool $isValid)
    {
        $this->header = $header;
        $this->payload = $payload;
        $this->signature = $signature;
        $this->isValid = $isValid;
    }

    /** Create instance of class Saiks24\JWT
     * @param String $data
     * @return JWT
     * @throws JWTWrongFormat
     * @throws MissingConfiguration
     */
    public static function create(String $data) : self
    {
        list($header,$payload,$signature) = explode('.',$data);
        if(empty($header) || empty($payload) || empty($signature)) {
            throw new JWTWrongFormat('Not valid JWT format');
        }
        $isValid = self::verify($header,$payload,$signature);
        return new JWT(base64_decode($header),base64_decode($payload),base64_decode($signature),base64_decode($isValid));
    }

    public function isOld()
    {
        return time() > $this->payload['invalidate'];
    }

    /** Verify JWT Token
     * @param $header
     * @param $payload
     * @param $signature
     * @return bool
     * @throws MissingConfiguration
     */
    private static function verify($header,$payload,$signature) : bool
    {
        $privateKey = App::getConfig()['secret'];
        if(empty($privateKey)) {
            throw new MissingConfiguration('Configuration file not init');
        }
        $ourSignature = hash_hmac('sha256',$header.'.'.$payload,$privateKey,false);
        return $ourSignature === $signature;
    }

    public function setStorageStrategy(Persisted $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param $id
     *
     * @return \Saiks24\JWT\JWT
     */
    public function get($id): JWT
    {
        return $this->storage->get($id);
    }

    /**
     * @param                  $id
     * @param \Saiks24\JWT\JWT $jwt
     *
     * @return bool
     */
    public function add($id, JWT $jwt): bool
    {
        return $this->storage->add($id,$jwt);
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function remove($id): bool
    {
        return $this->storage->remove($this);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->header.'.'.$this->payload.'.'.$this->signature;
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @return string
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }
}
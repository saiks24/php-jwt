<?php
namespace Saiks24\JWT;

use Saiks24\JWT\Exceptions\JWTWrongFormat;
use Saiks24\JWT\Exceptions\MissingConfiguration;

class JWT
{
    /** @var string  */
    private $header;
    /** @var string  */
    private $payload;
    /** @var string  */
    private $signature;
    /** @var boolean  */
    private $isValid;

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

    /**
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
        return new JWT($header,$payload,$signature,$isValid);
    }

    /**
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

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

}
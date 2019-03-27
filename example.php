<?php
require_once __DIR__.'/vendor/autoload.php';

$header  = base64_encode(json_encode([
    'typ'=>'JWT',
    'alg'=>'HS256'
]));
$payload = base64_encode(json_encode([
    'role'=>1,
    'id'=>1,
    'invalidate' => time()-10
]));
$refreshTokens = [];
$signature = hash_hmac('sha256',$header.'.'.$payload,'testSecret',false);
$jwt  =  $header . '.' . $payload . '.' . $signature;
var_dump($jwt);
$jwtObject = \Saiks24\JWT\JWT::create($jwt);
$jwtObject->setStorageStrategy(new \Saiks24\JWT\Storage\RedisStorage());
$i = 0;
$redis = new \Saiks24\JWT\Storage\RedisStorage([
  'address' => '0.0.0.0'
]);
while (true) {
    $jwtObject = \Saiks24\JWT\JWT::create($jwt);
    $jwtObject->setStorageStrategy($redis);
    var_dump($i);
    if($i == 50000) {
        break;
    }
    $jwtObject->add($i,$jwtObject);
    $i++;
}
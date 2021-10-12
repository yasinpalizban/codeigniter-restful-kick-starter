<?php

use Firebase\JWT\JWT;


function getJWTHeader(string $token): string
{
    //JWT is sent from client in the format Bearer XXXXXXXXX
    $explodeToken = explode(' ', $token);
    if (isset($explodeToken[1])) {
        return $explodeToken[1];
    }
    return '';

}

function validateJWT(string $token, string $key)
{

    $decodedToken = JWT::decode($token, $key, ['HS256']);

    return $decodedToken;
}

function timeJwt(bool $flag): array
{
    $time = time();
    if ($flag == true) {
        $expire = $time + (2 * DAY);
    } else {
        $expire = $time + (2 * 3600);
    }
    return ['init' => $time,
        'expire' => $expire
    ];
}

function generateJWT(string $userId, string $init, string $expire, string $key): string
{


    $payload = [

        "iss" => '', // this can be the servername. Example: https://domain.com
        'aud' => 'http://example.com',//THE_AUDIENCE
        'iat' => $init, // issued at
//        'nbf' => $time + 1,//not before in seconds
        'exp' => $expire,// expire time in seconds
        'userId' => $userId,

    ];


    /**
     * IMPORTANT:
     * You must specify supported algorithms for your application. See
     * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
     * for a list of spec-compliant algorithms.
     */
    $jwt = JWT::encode($payload, $key);

    return $jwt;
}

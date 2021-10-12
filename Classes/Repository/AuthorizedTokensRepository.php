<?php

namespace Repository;

use Database\MySQL;
use DateTime;
use InvalidArgumentException;
use Util\ConstantsGenericsUtil;

class AuthorizedTokensRepository
{
    private object $MySQL;

    public const TABLE = 'tokens_autorizados';
    private const KEY = "qS;T'7-%V`['NvXhY;Z$!(]EfZFB!FjwLqBb6N`j/2EF!m.Ck]qr{4`<Y;`*{Ec8B%R5g)";
    private const VALIDATE_TIME = 3600; // 3600s => 60min

    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    public function generateToken($id)
    {
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $payload = [
            'exp' => (new DateTime("now"))->getTimestamp(),
            'uid' => $id,
        ];

        $header = json_encode($header);
        $payload = json_encode($payload);

        $header = base64_encode($header);
        $payload = base64_encode($payload);

        $sign = hash_hmac('sha256', $header . "." . $payload, self::KEY, true);
        $sign = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($sign));

        $token = $header . '.' . $payload . '.' . $sign;

        return $token;
    }

    public function validateToken($token)
    {
        $token = str_replace([' ', "Bearer"], '', $token);
        if ($token) {
            $fragments = explode(".", $token);

            $sign = hash_hmac('sha256', $fragments[0] . "." . $fragments[1], self::KEY, true);
            $sign = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($sign));

            if ($fragments[2] == $sign) {
                $payload = json_decode(base64_decode($fragments[1]));

                if (($payload->exp + self::VALIDATE_TIME) >= (new DateTime("now"))->getTimestamp()) {
                    $updatedToken = $this->generateToken($payload->uid);
                    return $updatedToken;
                } else {
                    throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_TOKEN_NAO_AUTORIZADO);
                }
            } else {
                throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_TOKEN_NAO_AUTORIZADO);
            }
        } else {
            throw new InvalidArgumentException(ConstantsGenericsUtil::MSG_ERRO_TOKEN_VAZIO);
        }
    }

    public function getMySQL()
    {
        return $this->MySQL;
    }
}

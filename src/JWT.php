<?php

use Firebase\JWT\JWT as FJWT;

class Jwt {
    private string $secret;

    public function __construct() 
    {
      $this->secret = getenv('JWT_SECRET');
    }

    public function generateToken(int $userId): string 
    {
      $payload = [
        'iat' => time(),
        'exp' => time() + (60 * 60),
        'data' => [
          'user_id' => $userId
        ]
      ];

      $jwt = FJWT::encode($payload, $this->secret);

      return $jwt;
    }

    public function validateToken(string $token): bool|int 
    {
      try {
        $decoded = FJWT::decode($token, $this->secret, ['HS256']);
        return $decoded->data->user_id;
      } catch (Exception $e) {
        return false;
      }
    }
}

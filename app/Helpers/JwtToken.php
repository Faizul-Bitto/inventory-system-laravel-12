<?php

namespace App\Helpers;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtToken {
    public static function createToken( $userEmail, $userId ) {

        $key = env( 'JWT_KEY' );

        $payload = [
            'iss'       => 'laravel-token',
            'iat'       => time(),
            'exp'       => time() + 60 * 60 * 24 * 30,
            'userEmail' => $userEmail,
            'userId'    => $userId,
        ];

        return JWT::encode( $payload, $key, 'HS256' );
    }

    public static function verifyToken( $token ) {
        try {
            return $token == null ? 'unauthorized' : JWT::decode( $token, new Key( env( 'JWT_KEY' ), 'HS256' ) );
        } catch ( Exception $e ) {
            return 'unauthorized';
        }
    }

    public static function createTokenForResetPassword( $userEmail ) {
        $key = env( 'JWT_KEY' );

        $payload = [
            'iss'       => 'laravel-token',
            'iat'       => time(),
            'exp'       => time() + 60 * 60 * 24 * 30,
            'userEmail' => $userEmail,
            'userId'    => '0',
        ];

        return JWT::encode( $payload, $key, 'HS256' );
    }
}
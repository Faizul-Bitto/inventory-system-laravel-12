<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\JwtToken;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle( Request $request, Closure $next ): Response {
        //? we will collect the token to 'cookie'
        $token = $request->cookie( 'token' );

        //? we will verify the token, if the token is not valid, then it will return 'unauthorized'
        $result = JwtToken::verifyToken( $token );

        if ( $result == 'unauthorized' ) {
            return response()->json( ['message' => 'unauthorized'], 401 );
        }

        //? if the token is valid, then we will set the 'userEmail' and 'userId' to 'header'
        $request->headers->set( 'userEmail', $result->userEmail );
        $request->headers->set( 'userId', $result->userId );

        //? then we will let the request to pass
        return $next( $request );
    }

}

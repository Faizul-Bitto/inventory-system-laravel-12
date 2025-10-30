<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\JwtToken;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddlewareForAPI {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle( Request $request, Closure $next ): Response {
        //? we will collect the token from 'header'
        $token = $request->header( 'token' );

        //? we will verify the token, if the token is not valid, then it will return 'unauthorized'
        $result = JwtToken::verifyToken( $token );

//? if not valid then $result == 'unauthorized' ( it is coming from the Helper/JWTToken.php file's 'verifyToken()' method). That means, 'unauthorized' == 'unauthorized', so, the condition will satisfy and it will return 'unauthorized' with status code 401
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

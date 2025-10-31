<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helpers\JwtToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function userRegistration( Request $request ) {

        try {
            $request->validate( [
                'name'     => 'required',
                'mobile'   => 'required',
                'email'    => 'required',
                'password' => 'required',
            ] );

            $name     = $request->input( "name" );
            $mobile   = $request->input( "mobile" );
            $email    = $request->input( "email" );
            $password = $request->input( "password" );

            $user = User::create( [
                'name'     => $name,
                'mobile'   => $mobile,
                'email'    => $email,
                'password' => Hash::make( $password ),
            ] );

            return response()->json( [
                'status'  => 'success',
                'message' => 'User registered successfully',
                'user'    => $user,
            ], 201 );

        } catch ( Exception $e ) {
            return response()->json( [
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ], 500 );
        }

    }

    public function userLogin( Request $request ) {

        $request->validate( [
            'email'    => 'required|email',
            'password' => 'required',
        ] );

        $email    = $request->input( 'email' );
        $password = $request->input( 'password' );

        $user = User::where( 'email', '=', $email )->first();

        if ( $user && Hash::check( $password, $user->password ) ) {
            $token = JwtToken::createToken( $request->input( 'email' ), $user->id );

            return response()->json( [
                'status'  => 'success',
                'message' => 'User logged in successfully',
                'user'    => $user,
                'token'   => $token,
            ], 200 )->cookie( 'token', $token, time() + 60 * 24 * 30 );
        } else {
            return response()->json( [
                'status'  => 'failed',
                'message' => 'User not found',
            ], 404 );
        }

    }

}

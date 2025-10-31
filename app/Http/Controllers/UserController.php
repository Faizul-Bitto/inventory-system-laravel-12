<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\OtpMail;
use App\Helpers\JwtToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

    public function sendOTP( Request $request ) {

        $email = $request->input( 'email' );
        $otp   = rand( 1000, 9999 );
        $user  = User::where( 'email', '=', $email )->first();

        if ( $user ) {
            Mail::to( $email )->send( new OtpMail( $otp ) );
            User::where( 'email', '=', $email )->update( ['otp' => $otp] );

            return response()->json( [
                'status'  => 'success',
                'message' => "4 digit OTP {$otp} sent to your email address",
            ], 200 );
        } else {
            return response()->json( [
                'status'  => 'failed',
                'message' => 'User not found',
            ], 404 );
        }

    }

    public function verifyOTP( Request $request ) {

        $email = $request->input( 'email' );
        $otp   = $request->input( 'otp' );
        $user  = User::where( 'email', '=', $email )
            ->where( 'otp', '=', $otp )
            ->first();

        if ( $user ) {
            User::where( 'email', '=', $email )->update( ['otp' => '0'] );
            $token = JwtToken::createTokenForResetPassword( $request->input( 'email' ) );

            return response()->json( [
                'status'  => 'success',
                'message' => 'OTP verified successfully',
                'token'   => $token,
            ], 200 )->cookie( 'token', $token, time() + 60 * 24 * 30 );
        } else {
            return response()->json( [
                'status'  => 'failed',
                'message' => 'Invalid OTP',
            ], 404 );
        }

    }

    public function resetPassword( Request $request ) {
        try {
            $email    = $request->header( 'userEmail' );
            $password = $request->input( 'password' );
            $user     = User::where( 'email', '=', $email )
                ->update( ['password' => $password] );

            return response()->json( [
                'status'  => 'success',
                'message' => 'Password reset successfully',
                'user'    => $user,
            ], 200 );
        } catch ( Exception $e ) {
            return response()->json( [
                'status'  => 'failed',
                'message' => 'Something went wrong',
            ], 500 );
        }

    }

    public function userProfileDetails( Request $request ) {
        $email = $request->header( 'email' );
        $user  = User::where( 'email', '=', $email )->first();

        return response()->json( [
            'status'  => 'success',
            'message' => 'Request Successful',
            'user'    => $user,
        ], 200 );
    }

    public function updateUserProfile( Request $request ) {

    }

}

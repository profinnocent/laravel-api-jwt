<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
// use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //public constructor
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }


    //For login
    public function login(Request $request){
        $request->validate([
            'email' => 'required | string | email',
            'password' => 'required | string',
        ]);

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if(!$token){

            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorised'
            ], 401);

        }

        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer'
            ]
            ]);
    }


    //Register
    public function register(Request $request){
        // Validate user inputs
        $request->validate([
            'name' => 'required | string | max:225',
            'email' => 'required | string | max:225 | unique:users',
            'password' => 'required | string | min:6',
        ]);

        // Create a user with validated inputs
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    

        // Verify user token
        $token = Auth::login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
            ]);
    }


    // Logout function
    public function logout(){

        Auth::logout();

        return response()->json([
            'status' => 'success',
            'message' => 'User successfully logged out',
        ]);

    }


    // Refresh token
    public function refresh(){

        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',            ]
            ]);
    }
    
};

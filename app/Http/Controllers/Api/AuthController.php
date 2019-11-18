<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\User;

class AuthController extends Controller
{   

    // REGISTER
    public function register(Request $request){
        $validatedData = $request -> validate([
            'name'=>'required|max:55',
            'email'=>'email|required|unique:users',
            'password'=> 'required'
        ]);
            $validatedData['password'] = bcrypt($request['password']);
            $user = User::create($validatedData);

            $accessToken = $user->createToken('authToken')->accessToken;

            return response(['user'=>$user, 'token'=>$accessToken]);
    }


    // LOGIN
    public function login(Request $request){
        $loginData = $request -> validate([
            'email'=>'email|required',
            'password'=> 'required'
        ]);

        if(!auth()->attempt($loginData)){
            return response(['message'=>'Invalid Credentials']);
        }
        $user = auth()->user();
        $accessToken = $user->createToken('authToken')->accessToken;

            return response(['user'=>$user, 'token'=>$accessToken]);

    }
}

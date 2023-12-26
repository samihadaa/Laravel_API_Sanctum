<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){
       $userFields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' =>'required|string|confirmed'
        ]);
        $user = User::create([
            'name' => $userFields['name'],
            'email' => $userFields['email'],
            'password' => bcrypt($userFields['password']),
        ]);
        $token = $user->createToken('userToken')->plainTextToken;
        $response=[
            'user' => $user,
            'token' => $token
        ];
        return response($response,201);
    }
    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|string',
            'password' =>'required|string',
        ]);
        //check email
        $user = User::where('email','=',$fields['email'])->first();
        //check password
        if(!$user){
            return response()->json(['success'=>false, 'message' => 'Login Fail, please check email']);
        }

      if(!Hash::check($fields['password'],$user->password)){
        return response()->json(['success'=>false, 'message' => 'Login Fail, please check password']);
      }
      $token = $user->createToken('userToken')->plainTextToken;
      $response=[
        'user' => $user,
        'token' =>$token
      ];
        return response($response,200);
    }
    public function logout(Request $request){
       auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }
}

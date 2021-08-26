<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException as ValidationValidationException;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationValidationException::withMessages([
                'message' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('UserToken')->plainTextToken;

        return Response()->json([
            'message'=>'Authorized',
            'user' => $user,
            'token' => $token
        ],200);
    }

    public function logout(Request $request){
        //take user data form request
        $user =  $request->user();
        //delete token user 
        $user->tokens()->delete();

        return Response()->json([
            'message'=>'logout',
        ],200);
    }
}

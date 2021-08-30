<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rules;

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

    public function register(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            'message' => 'user created',
            'token' => $token
        ];

        return response($response,201);
    }

    public function show($id)
    {
        $activity = User::findOrFail($id);
        $response = [
            'message' => 'Detail Of Activity',
            'data' => $activity
        ];

        return response()->json($response, Response::HTTP_OK);
    }
    
}

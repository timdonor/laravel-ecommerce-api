<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except'=> ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=> 'required|email',
            'password'=> 'required|string|min:6',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            if (! $token = JWTAuth::attempt($validator->validated())) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return $this->createNewToken($token);

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required|string|between:2,100',
            'email'=> 'required|string|email|max:100|unique:users',
            'password'=> 'required|string|min:6'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password'=> bcrypt($request->password)]
        ));

        return response()->json([
            'message'=> 'User successfully registered',
            'user'=> $user
        ], 201);
    }

    public function logout()
    {
        JWTAuth::logout();
        return response()->json(['message'=> 'User Succcessfully signed out']);
    }

    public function refresh()
    {
        return $this->createNewToken(JWTAuth::refresh());
    }

    public function userProfile()
    {
        return response()->json(JWTAuth::user());
    }

    protected function createNewToken($token)
    {
        return response()->json([
            "access_token"=> $token,
            "token_type"=> "Bearer",
            "expires_in"=> auth()->factory()->getTTL() * 60,
            "user"=> JWTAuth::user()
        ]);
    }

}

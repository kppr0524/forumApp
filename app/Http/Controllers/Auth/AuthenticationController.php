<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    //function to register user
    public function register(RegisterRequest $request)
    {
        //validate the request
        $request->validated();

        $userData = [
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];

        $user = User::create($userData);
        $token = $user->createToken('forumApp')->plainTextToken;

        //return user details
        return response([
            'user' => $user,
            'token' => $token
        ], 200
        );

    }    

    public function login(LoginRequest $request)
    {
        //validate the request
        $request->validated();

        //check if the user exists
        $user = User::where('username', $request->username)->first();

        //check if the password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Invalid password or username. Please try again.'
            ], 401);
        }

        $token = $user->createToken('forumApp')->plainTextToken;

        //return user details
        return response([
            'user' => $user,
            'token' => $token
        ], 200
        );
    }

}

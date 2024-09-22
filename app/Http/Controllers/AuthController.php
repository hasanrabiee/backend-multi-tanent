<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginUserRequest;
use App\Http\Requests\auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Summary of AuthController
 */
class AuthController extends Controller
{
    /**
     * Summary of register
     * @param RegisterUserRequest $request
     * @return array
     */
    public function register(RegisterUserRequest $request)
    {
        $validatedRequest = $request->validated();
        $user = User::create($validatedRequest);

        if ($validatedRequest['email'] === 'admin@admin.com') {
            $user->role = 'admin';
            $user->save();      
        }

        $token = $user->createToken($request->name);
        return [
            "user" => $user,
            "token" => $token->plainTextToken
        ];
    }

    public function login(LoginUserRequest $request)
    {
        $request->validated();
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'the provided credeintial are incorrect'
            ], 401);
        };
        $token = $user->createToken($user->name);
        return [
            "user" => $user,
            "token" => $token->plainTextToken
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "you are logged out "
        ]);
    }
}

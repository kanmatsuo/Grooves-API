<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'fix errors', 'errors' => $validator->errors()], 500);
        }

        if (!Auth::attempt($attr)) {
            return response()->json([
                'status' => false,
                'message' => 'fix errors',
                'errors' => [
                    'email' => ['Unauthorized Field'],
                    'password' => ['Unauthorized Field']
                ]
            ], 401);
        }

        $token = auth()->user()
            ->createToken('auth_token')->plainTextToken;
        $user = auth()->user();

        $respon = [
            'status' => 'success',
            'msg' => 'Login successfully',
            'content' => [
                'access_token' => $token,
                'user' => $user
            ],
            'code' => 200
        ];

        return response()->json($respon, $respon['code']);
    }

    public function logout(Request $request)
    {
        auth()->guard('api')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['status' => true, 'msg' => 'Logout successfully']);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * create new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid('profile_img_') . '.' . $extension;
            $file->storeAs('public/profile/users', $fileName);
            if ($user->avatar != null) {
                Storage::delete('public/profile/users/' . $user->avatar);
            }
            $user->avatar = $fileName;
        }
        if ($request->name != '') {
            $validator = Validator::make($request->all(), [
                'name' => ['string', 'max:255']
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'fix errors', 'errors' => $validator->errors()], 500);
            }

            $user->name = $request->name;
        }
        if ($request->email != '') {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'fix errors', 'errors' => $validator->errors()], 500);
            }

            $user->email = $request->email;
        }
        if ($request->email == '' && $request->name == '' && !$request->hasFile('file')) {
            return response()->json([
                'status' => false,
                'message' => 'fix errors',
                'errors' => [
                    'name' => ['No Changed.'],
                    'email' => ['No Changed.'],
                    'file' => ['No Changed.']
                ]
            ], 500);
        }

        $user->save();
        return response()->json(['status' => true, 'user' => $user]);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'old' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'fix errors', 'errors' => $validator->errors()], 500);
        }

        if (Hash::check($request->old, $user->password)) {
            $user->fill([
                'password' => Hash::make($request->password)
            ])->save();
            return response()->json(['status' => true, 'user' => $user]);
        } else {
            return response()->json(['status' => false, 'message' => 'fix errors', 'errors' => ['old' => ['Old Password is not match.']]], 500);
        }
    }
}
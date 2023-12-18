<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function register(Request $request) {
        try {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'roles' => $request->input('roles'),
        ]);

        return response([
            'message' => 'User registered successfully',
            'user' => $user,
        ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
        return response([
            'error' => 'Registration failed',
            'message' => $e->getMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request) {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60 * 24);

        return response([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ])->withCookie($cookie);
    }

    public function user() {
        return Auth::user();
    }

    public function logout() {
        $cookie = Cookie::forget('jwt');

        return response ([
            'message' => 'Logout Successs'
        ])->withCookie($cookie);
    }

    public function updateName(Request $request)
    {
        return $this->updateProfileField($request, 'name', 'Name');
    }

    public function updatePassword(Request $request)
    {
    try {
        // Validate the request data
        $request->validate([
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:8',
            'confirmPassword' => 'required|string|same:newPassword',
        ]);

        // Verify the current password
        if (!Hash::check($request->input('currentPassword'), $user->password)) {
            return response([
                'error' => 'Password update failed',
                'message' => 'Current password is incorrect',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Update the password
        $user->password = Hash::make($request->input('newPassword'));
        
        $user->save();

        return response([
            'message' => 'Password updated successfully',
            'user' => $user,
        ]);
    } catch (\Exception $e) {
        return response([
            'error' => 'Password update failed',
            'message' => $e->getMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

    public function updateAddress(Request $request)
    {
        return $this->updateProfileField($request, 'address', 'Address');
    }

    private function updateProfileField(Request $request, $field, $fieldName)
    {
        try {
            $user = Auth::user();

            // Validate the request data
            $request->validate([
                $field => 'required|string|max:255',
            ]);

            // Update the profile field
            $user->{$field} = $request->input($field);
            $user->save();

            return response([
                'message' => "{$fieldName} updated successfully",
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => "{$fieldName} update failed",
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

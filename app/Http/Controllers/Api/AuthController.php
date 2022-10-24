<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate the user input
        // In case of validation fail it return proper error message
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'confirmed',
                // Sets the minimum size of the password
                Rules\Password::min(8)
                    // Makes the password require at least one uppercase and one lowercase letter
                    ->mixedCase()
                    // Makes the password require at least one number
                    ->numbers()
                    // EMakes the password require at least one symbol (@#$%&!)
                    ->symbols()
                    // Ensures the password has not been compromised in data leaks.
                    ->uncompromised(),
            ],
        ]);

        // Create a new user with validated input
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            // Hash the given password against the bcrypt algorithm
            'password' => bcrypt($validated['password'])
        ]);

        // Return success message with 201 HTTP code
        return response()->json([
            'message' => 'User created successfully',
        ], 201);
    }

    public function login(Request $request)
    {
        // Validate the user login input
        // In case of validation fail it return proper error message
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Verify if validade credentials are correct
        if (!auth()->attempt($validated)) {
            // If fail return a generic login error message that not say if you have the passowrd or email worng
            throw ValidationException::withMessages([
                'email' => trans('auth.failed')
            ]);
        }

        // Get the user with the validated email
        $user = User::where('email', $validated['email'])->first();

        // Destroy all old tokens for this user
        // Used to lougout other devices
        // $user->tokens()->delete();

        // Create a token for this user
        $token = $user->createToken('auth-token')->plainTextToken;

        // Return the token with 200 HTTP code
        return response()->json([
            'token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        // Destroy all tokens for this user
        // Used to lougout other devices
        // $request->user()->tokens()->delete();

        // Destroy only the token current used
        $request->user()->currentAccessToken()->delete();

        // Return no data with 204 HTTP code
        return response()->json([], 204);
    }
}

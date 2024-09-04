<?php

namespace App\Http\Controllers\api\admin\auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {


        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:4'
        ]);
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Credentials'
                ]);
            }

            $token = $user->createToken('MyApp')->plainTextToken;

            return response()->json([
                'status' => true,
                "token" => $token,
                "user" => $user,
                "message" => 'Login successfully',
            ]);

        } catch (\Exception $e) {

            if (config('app.debug')) {
                // Return detailed error information in development
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(), // Optional: Include stack trace for debugging
                ], 500); // Internal Server Error status code
            } else {
                // Return a generic error message in production
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong. Please try again later.',
                ], 500);
            }
        }



    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);

    }
}

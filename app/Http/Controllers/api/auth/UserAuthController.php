<?php

namespace App\Http\Controllers\api\auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'min:8|required_with:confirmed_password|same:confirmed_password',
            'confirmed_password' => 'min:8'
        ]);

        try {
            $requestData = $request->all();
            $requestData['user_type'] = User::USER_TYPE_SELLER;
            $requestData['password'] = Hash::make($requestData['password']);
            $user = User::create($requestData);
            $token = $user->createToken('MyApp')->plainTextToken;

            return response()->json([
                'status' => true,
                "token" => $token,
                "user" => $user,
                "message" => 'Register successfully',
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
    public function login(Request $request)
    {


        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:4'
        ]);
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password) || $user->user_type != User::USER_TYPE_USER ) {
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


}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $school = School::where('login_id', $request->login_id)->first();

        if (!$school || !Hash::check($request->password, $school->password)) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $token = $school->createToken('school-api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }
}

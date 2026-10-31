<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SchoolApiController extends Controller
{
    public function show(Request $request)
    {
        $school = $request->user();

        $school->load([
            'state',
            'district',
            'city',
            'photos',
            'students.standard',
        ]);

        return response()->json([
            'success' => true,
            'school' => $school,
        ]);
    }
}

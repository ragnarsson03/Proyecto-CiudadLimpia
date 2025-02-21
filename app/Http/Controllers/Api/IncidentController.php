<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\JsonResponse;

class IncidentController extends Controller
{
    public function index(): JsonResponse
    {
        $incidents = Incident::select('id', 'title', 'description', 'latitude', 'longitude', 'status')
            ->with('status:id,name,color')
            ->get();

        return response()->json($incidents);
    }
} 
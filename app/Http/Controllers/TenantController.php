<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     * Show all tenant.
     */
    public function index()
    {
        // Fetch all posts with their associated user
        $tenents = Tenant::all();

        return response()->json($tenents);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            // Create a new post for the authenticated user
            $tenant = Tenant::query()->create(
                attributes: [
                    'id' => $request->name
                ]
            );
            $tenant->domains()->create(
                attributes: [
                    'domain' => "{$request->name}.localhost"
                ]
            );

            return response()->json([
                'message' => 'tenant created successfully!',
                'tenant' => $tenant
            ], 201); // Status code 201 for created resource
        } catch (QueryException $e) {
            // Check if the error is due to a unique constraint violation (SQLSTATE 23000)
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['error' => 'Tenant with this ID already exists. Please use a different ID.'], 400);
            }

            // Handle other types of database errors
            return response()->json(['error' => 'Database error occurred. Please try again.'], 500);
        }
    }
}

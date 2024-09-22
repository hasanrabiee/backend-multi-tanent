<?php

namespace App\Http\Controllers;

use App\Http\Requests\admin\CreateRuleRequest;
use App\Models\ContentRule;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Requests\admin\UpdateRuleRequest;
use Illuminate\Routing\Controllers\Middleware;

class RuleController extends Controller
{
    // Ensure the user is an admin for most routes
    public static function middleware(): array
    {
        return [
            new Middleware(middleware: AdminMiddleware::class, except: ['index', 'showCountryNotifs']),

        ];
    }

    /**
     * Display a listing of the resource.
     * Show all rules.
     */
    public function index()
    {
        // Fetch all rules
        $rules = ContentRule::all();

        return response()->json($rules);
    }

    /**
     * Store a newly created resource in storage.
     * Create a new rule.
     */
    public function store(CreateRuleRequest $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validated();

        // Create a new rule
        $rule = ContentRule::create($validatedData);

        return response()->json([
            'message' => 'Rule created successfully!',
            'rule' => $rule
        ], 201); // Status code 201 for created resource
    }

    /**
     * Display the specified resource.
     * Show a specific rule.
     */
    public function show(ContentRule $rule)
    {
        return response()->json($rule);
    }

    /**
     * Display the specified resource.
     * Show a specific rule.
     */
   


    /**
     * Update the specified resource in storage.
     * Update an existing rule.
     */
    public function update(UpdateRuleRequest $request, ContentRule $rule)
    {
        // Validate the incoming request data
        $validatedData = $request->validated();

        // Update the rule with the validated data
        $rule->update($validatedData);

        return response()->json([
            'message' => 'Rule updated successfully!',
            'rule' => $rule
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * Delete a specific rule.
     */
    public function destroy(ContentRule $rule)
    {
        // Delete the rule
        $rule->delete();

        return response()->json([
            'message' => 'Rule deleted successfully',
        ]);
    }

    public function showCountryNotifs(Request $request)
    {
        // Find the content rule by country
        $rule = ContentRule::where('country', $request->user()->country)->get();

        // If the rule does not exist, return a 404 response
        if (!$rule) {
            return response()->json(['message' => 'Rule not found'], 404);
        }

        // Return the found rule
        return response()->json($rule);
    }

}

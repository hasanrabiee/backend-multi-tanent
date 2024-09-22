<?php

declare(strict_types=1);

use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\RulesController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
    Route::get('/rules/notifs', [RuleController::class,'showCountryNotifs'])->middleware('auth:sanctum');
    Route::apiResource('/rules', RuleController::class);


    Route::apiResource('posts', PostController::class);
    Route::get('/post/images/{filename}', [PostController::class, 'getPostImage']);


    Route::post("/register", [AuthController::class, 'register']);
    Route::post("/login", [AuthController::class, 'login']);
    Route::post("/logout", [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

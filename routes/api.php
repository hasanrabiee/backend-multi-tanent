<?php

use App\Http\Controllers\RulesController;
use App\Http\Controllers\TenantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/tenants', [TenantController::class,'index']);
        Route::post('/tenants', [TenantController::class,'store']);

    });
}

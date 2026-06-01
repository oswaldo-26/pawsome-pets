<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PetApiController;
use App\Http\Controllers\Api\AdoptionApiController;

Route::apiResource('pets', PetApiController::class);
Route::apiResource('adoption-requests', AdoptionApiController::class);
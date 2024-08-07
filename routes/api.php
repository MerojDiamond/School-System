<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::post("login", [MainController::class, "login"]);
Route::post("register", [MainController::class, "register"]);
Route::get("registerData/{id}", [MainController::class, "registerData"]);
Route::middleware('auth:sanctum')->group(function () {
    Route::post("logs", [MainController::class, "logs"])->middleware("role:Super-Admin");
    Route::get("log/{id}", [MainController::class, "log"])->middleware("role:Super-Admin");
    Route::post("settings", [MainController::class, "settings"]);
    Route::get("settings/{id}", [MainController::class, "getSettings"]);
});

<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post("users", [UserController::class, "index"])->middleware("can:show-users");
    Route::put("update-user/{user}", [UserController::class, "update"])->middleware("can:edit-users");
    Route::delete("delete-user/{user}", [UserController::class, "delete"])->middleware("can:delete-users");
    Route::post("trashed", [UserController::class, "trashed"])->middleware("can:show-trashed-users");
    Route::get("restore-user/{user}", [UserController::class, "restore"])->middleware("can:restore-users");
    Route::delete("delete-forever-user/{user}", [UserController::class, "destroyForever"])->middleware("can:delete-forever-users");
});

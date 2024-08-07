<?php

use Illuminate\Support\Facades\Route;
use Modules\Role\Http\Controllers\RoleController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get("roles", [RoleController::class, "allRoles"])->middleware("can:show-roles");
    Route::post("roles", [RoleController::class, "index"])->middleware("can:show-roles");
    Route::get("permissions", [RoleController::class, "permissions"]);
    Route::post("add-role", [RoleController::class, "store"])->middleware("can:create-roles");
    Route::put("update-role/{role}", [RoleController::class, "update"])->middleware("can:edit-roles");
    Route::delete("delete-role/{role}", [RoleController::class, "delete"])->middleware("can:delete-roles");
});

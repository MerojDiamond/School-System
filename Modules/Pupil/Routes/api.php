<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/pupil', function (Request $request) {
    return $request->user();
});

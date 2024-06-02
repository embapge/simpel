<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return response()->json($request->user() ?? $request->ip(), 200);
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/asaskdakssajkd', function (Request $request) {
    return response()->json("masuk");
    return response()->json($request->user() ?? $request->ip(), 200);
    return $request->user();
});

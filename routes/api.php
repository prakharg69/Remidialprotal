<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->get('/status', function () {
        return response()->json(['status' => 'ok']);
    });

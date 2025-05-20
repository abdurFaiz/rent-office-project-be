<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/up', function () {
    return response('OK', 200);
});

// Debug database connection - hapus atau amankan di production!
Route::get('/debug-db', function () {
    try {
        $dbConfig = [
            'connection' => config('database.default'),
            'host' => config('database.connections.' . config('database.default') . '.host'),
            'database' => config('database.connections.' . config('database.default') . '.database'),
            'username' => config('database.connections.' . config('database.default') . '.username'),
            'env_vars' => [
                'RAILWAY_PRIVATE_DOMAIN' => env('RAILWAY_PRIVATE_DOMAIN'),
                'POSTGRES_DB' => env('POSTGRES_DB'),
                'POSTGRES_USER' => env('POSTGRES_USER'),
                'DB_CONNECTION' => env('DB_CONNECTION'),
                'DB_HOST' => env('DB_HOST'),
                'DB_DATABASE' => env('DB_DATABASE'),
                'DB_USERNAME' => env('DB_USERNAME'),
                'DATABASE_URL' => env('DATABASE_URL') ? 'set' : 'not set'
            ]
        ];
        
        // Test connection
        try {
            DB::connection()->getPdo();
            $connected = true;
        } catch (\Exception $e) {
            $connected = false;
            $error = $e->getMessage();
        }
        
        return response()->json([
            'config' => $dbConfig,
            'connected' => $connected,
            'error' => $error ?? null
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

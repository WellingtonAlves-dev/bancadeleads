<?php

use App\Http\Controllers\LeadsController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\MobileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// LEADS
Route::post("/importar", [LeadsController::class, "importarLeadsApi"]);
Route::post("/lead/excluir/{id_global}", [LeadsController::class, "excluirApi"]);

// Notificações
Route::any("notificacao/mercadopago/{unique_key}", [MercadoPagoController::class, "notificacao"]);
Route::any("backurl/{unique_key}",  [MercadoPagoController::class, "backUrls"]);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// MOBILE
Route::post("/mobile/token", [MobileController::class, "salvarToken"]);
Route::get("/mobile/token", [MobileController::class, "index"]);
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TokensMobile;
use Exception;
use Illuminate\Http\Request;

class MobileController extends Controller
{
    public function index() {
        $data = TokensMobile::all();
        return response()->json($data);
    }
    public function salvarToken(Request $request, TokensMobile $tokensMobile) {
        try {
            $token = $request->token;
            if(!TokensMobile::where("token", $token)->first()) {
                $tokensMobile->create([
                    "token" => $token
                ]);   
            } 
            return response(json_encode([
                "erro" => false,
                "msg" => "Token " . $token . " cadastrado com sucesso"
            ]));
        } catch (Exception $e) {
            return response(json_encode([
                "erro" => true,
                "msg" => $e->getMessage()
            ]));
        }
    }
}

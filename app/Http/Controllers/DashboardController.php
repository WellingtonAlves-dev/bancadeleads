<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        $dataHoje = date("Y-m-d");
        // AND date(created_at) = '2023-06-07'
        $totalLeadsVendidas = 
            DB::select("SELECT SUM(valor) as valor FROM saldos WHERE tipo = 'saida' AND observação LIKE '%COMPRA DE LEAD APROVADA%' AND date(created_at) = '{$dataHoje}'")[0]->valor;
        
        $totalEstorno = 
            DB::select("SELECT SUM(valor) as valor FROM saldos WHERE tipo = 'entrada' AND observação LIKE '%ESTORNO DA LEAD # %' AND date(created_at) = '{$dataHoje}'")[0]->valor;

        $totalLeadsVendidas = $totalLeadsVendidas - $totalEstorno;

        $totalLeadsNaoVendidas = 
            DB::select("SELECT SUM(preco) as valor FROM leads WHERE leads.id NOT IN ( SELECT id_lead FROM minhas_leads) AND date(created_at) = '{$dataHoje}'")[0]->valor;
        $totalLeadsCadastradas = Lead::all()->count();
        $totalUsers = User::all()->count();

        $totalLeadsVendidas = number_format($totalLeadsVendidas, 2, ",", ".");
        $totalLeadsNaoVendidas = number_format($totalLeadsNaoVendidas, 2, ",", ".");
        $totalEstorno = number_format($totalEstorno, 2, ",", ".");

        return view("dashboard.index", compact(
            "totalLeadsVendidas", 
            "totalLeadsNaoVendidas", 
            "totalLeadsCadastradas",
            "totalEstorno", 
            "totalUsers")
        );
    }
}

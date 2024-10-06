<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Saldos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceiroController extends Controller
{
    public function meusExtrato() {
        $extratos = Saldos::where("user_id", Auth::user()->id)
        ->orderBy("id", "desc")
        ->paginate(20);
        $saldoAtual = Auth::user()->getValorSaldo(true);
        return view("pages.meusextratos", compact("extratos", "saldoAtual"));
    }

    public function extratos(Request $request) {
        $extratos = Saldos::from("saldos as s1")->where("tipo", "saida")
        ->where("observação", "LIKE", "%COMPRA DE LEAD APROVADA #%");
        $estornos = 0;
        
        if ($request->data_inicial && $request->data_final) {
            $dataInicial = date("Y-m-d", strtotime($request->data_inicial));
            $dataFinal = date("Y-m-d", strtotime($request->data_final));
            
            $limiteDeDoisMeses = date("Y-m-d", strtotime("-2 months"));
            
            if ($dataInicial < $limiteDeDoisMeses) {
                $dataInicial = $limiteDeDoisMeses;
            }
            $extratos->whereBetween("created_at", [$dataInicial, $dataFinal])
            ->orderBy("id", "desc");

            $estornos = Saldos::where('tipo', 'entrada')
            ->where('observação', 'LIKE', '%ESTORNO DA LEAD # %')
            ->whereBetween('created_at', [$dataInicial, $dataFinal])
            ->sum('valor');        
        } else {
            $dataAtual = date("Y-m-d");
            $dataInicial = $dataAtual;
            $dataFinal = date("Y-m-d", strtotime($dataAtual . ' + 1 day'));        
            $extratos->whereDate("created_at", $dataAtual);  

            $estornos = Saldos::where('tipo', 'entrada')
            ->where('observação', 'LIKE', '%ESTORNO DA LEAD # %')
            ->whereDate('created_at', $dataAtual)
            ->sum('valor');    
        }

        $valorTotal = ( $extratos->sum("valor") ?? 0) - ( $estornos ?? 0);
        $extratos = $extratos->paginate(10);

        return view("pages.compras", compact("extratos", "valorTotal"));
    }
}

// R$ 8.706,78
// R$ 8.937,68

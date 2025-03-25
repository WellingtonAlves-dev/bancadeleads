<?php

namespace App\Exports;

use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class ExportsLead implements FromView
{
    private $data_inicial;
    private $data_final;

    public function __construct($data_inicial, $data_final) {
        $this->data_inicial = Carbon::parse($data_inicial)->startOfDay(); // Ajusta para 00:00:00
        $this->data_final = Carbon::parse($data_final)->endOfDay(); // Ajusta para 23:59:59
    }

    public function view() : View {
        $data_inicial = $this->data_inicial;
        $data_final = $this->data_final;

        $minhasLeads = Lead::select("leads.*", "minhas_leads.created_at as dataAquisicao")
            ->with("tipo", "plano")
            ->join("minhas_leads", "minhas_leads.id_lead", "=", "leads.id")
            ->where("minhas_leads.id_user", Auth::id()) // Garante que o usuário está autenticado
            ->whereBetween("minhas_leads.created_at", [$data_inicial, $data_final]) // Filtra corretamente as datas
            ->get();
        return view("exports.templates.minhasleads", [
            'leads' => $minhasLeads
        ]);
    }
}

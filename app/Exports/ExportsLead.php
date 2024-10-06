<?php

namespace App\Exports;

use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportsLead implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $data_inicial = null;
    private $data_final = null;
    public function __construct($data_inicial, $data_final) {
        $this->data_inicial = date($data_inicial);
        $this->data_final = date($data_final);
    }
    public function view() : View {

        $data_inicial = $this->data_inicial;
        $data_final = $this->data_final;


        $minhasLeads = Lead::select("leads.*", "minhas_leads.created_at as dataAquisicao")
        ->with("tipo", "plano")
        ->join("minhas_leads", "minhas_leads.id_lead", "leads.id")
        ->where("minhas_leads.id_user", Auth::user()->id)
        ->where(function ($query) use($data_inicial, $data_final) {
            $query->whereBetween("minhas_leads.created_at", [$data_inicial, $data_final])
                  ->orWhereDate("minhas_leads.created_at", $data_inicial)
                  ->orWhereDate("minhas_leads.created_at", $data_final);
        })->get();
        return view("exports.templates.minhasleads", [
            'leads' => $minhasLeads
        ]);
    }
}

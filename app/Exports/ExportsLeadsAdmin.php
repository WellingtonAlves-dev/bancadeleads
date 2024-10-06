<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportsLeadsAdmin implements FromView
{
    private $request;
    public function __construct($request) {
        $this->request = $request;
    }
    public function view() : View {
        $request = $this->request;
        $leads = Lead::select("leads.*")
        ->with("tipo", "plano"); 
        if($request->ddd) {
            $leads->where("ddd", $request->ddd);
        }
        if($request->data_inicial && $request->data_final) {
            $leads->whereBetween("leads.created_at", [$request->data_inicial, $request->data_final])
            ->orWhereDate("leads.created_at", $request->data_inicial)
            ->orWhereDate("leads.created_at", $request->data_final);    
        }
        $leads = $leads->get();
        return view("exports.templates.minhasleads", [
            "leads" => $leads
        ]);
    }
}

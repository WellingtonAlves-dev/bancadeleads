<?php

namespace App\Console\Commands;

use App\Http\Controllers\LeadsController;
use App\Models\MinhasLeads;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class sincLeadsVendidas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sinc-leads-vendidas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listar as leads vendidas nas ultimas X horas e remover das outras plataformas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $leadsController = new LeadsController();
        MinhasLeads::whereBetween("created_at", [Carbon::today(), Carbon::today()->endOfDay()])
            ->with("leadInfo")
            ->chunk(100, function($leads) use ($leadsController) {
                foreach ($leads as $lead) {
                    try {
                        if (!empty($lead->leadInfo) && !empty($lead->leadInfo->id_global)) {
                            $leadsController->excluirLeadExterna($lead->leadInfo->id_global);
                        }
                    } catch (Throwable $e) {
                        // log
                        Log::error("Erro ao excluir lead externa", [
                            'lead_id' => $lead->id ?? null,
                            'id_global' => $lead->leadInfo->id_global ?? null,
                            'message' => $e->getMessage(),
                            'trace'   => $e->getTraceAsString(),
                        ]);

                    }
                }
            });

    }
}

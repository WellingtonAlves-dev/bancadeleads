<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvisosController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CorretoresController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\Importadores;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\LeadsFriasController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\MinhasLeadsController;
use App\Http\Controllers\MobileController;
use App\Http\Controllers\NotificacoesPagamentoController;
use App\Http\Controllers\PacotesController;
use App\Http\Controllers\PlanosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReposicaoController;
use App\Http\Controllers\TemplateEmailController;
use App\Http\Controllers\TiposController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("index.php", function() {
    return redirect("/"); 
});

Route::get("cadastro.php", function() {
    return redirect("/signup");
});

Route::get("/login", [AuthController::class, "loginView"])->name("login");
Route::post("/login", [AuthController::class, "login"])->name("login");
Route::get("/signup", [AuthController::class, "signUpView"])->name("signup");
Route::post("/signup", [AuthController::class, "signup"]);
Route::get("/logout", [AuthController::class, "logout"]);
Route::get("/recovery", [AuthController::class, "recoveryPasswordView"]);
Route::get("/mail/recovery/{unique_code}", [AuthController::class, "newPasswordView"]);
Route::post("/mail/recovery", [AuthController::class, "sendcodeRecovery"]);
Route::post("/mail/new_password", [AuthController::class, "newPassword"]);
Route::get("/mail/confirm/{id}", [AuthController::class, "verifyMail"]);
Route::get("/privacidade", function() {
    return view("pages.privacidade");
});
Route::middleware(["auth"])->group(function() {
        Route::get("/user/retornar", [UserController::class, "retornarConta"]);
        Route::middleware(["roleValidate:admin"])->group(function() {
            //dashboard
            Route::get('/', [DashboardController::class, "index"]);
            //planos
            Route::get("/admin/planos", [PlanosController::class, "index"]);
            Route::get("/admin/planos/novo", [PlanosController::class, "novoView"]);
            Route::get("/admin/planos/editar/{id}", [PlanosController::class, "editarView"]);
            Route::post("/admin/planos/salvar/{id?}", [PlanosController::class, "salvar"]);
            //Tipos
            Route::get("/admin/tipos", [TiposController::class, "index"]);
            Route::get("/admin/tipos/novo", [TiposController::class, "novoView"]);
            Route::get("/admin/tipos/editar/{id}", [TiposController::class, "editarView"]);
            Route::post("/admin/tipos/salvar/{id?}", [TiposController::class, "salvar"]);
            //cadastro corretor
            Route::get("/admin/users/entrar/{id}", [UserController::class, "loginById"]);
            Route::get("/admin/users", [UserController::class, "index"]);
            Route::get("/admin/users/novo", [UserController::class, "novoView"]);
            Route::get("/admin/users/editar/{id}", [UserController::class, "editarView"]);
            Route::get("/admin/users/apagar/{id}", [UserController::class, "excluir"]);
            Route::post("/admin/users/salvar/{id?}", [UserController::class, "salvar"]);
            //Leads
            Route::get("/admin/leads/novo", [LeadsController::class, "novoView"]);
            Route::get("/admin/leads/editar/{id}", [LeadsController::class, "editarView"]);
            Route::get("/admin/leads/reset/{id}", [LeadsController::class, "resetHorarioPartida"]);
            Route::post("/admin/leads/salvar/{id?}", [LeadsController::class, "salvar"]);
            Route::post("/admin/leads/importar", [LeadsController::class, "importarLeads"]);
            Route::get("/admin/lead/{id_lead}/{status}", [LeadsController::class, "toggleLeadStatus"]);
            Route::get("/admin/leads/apagar/{id}", [LeadsController::class, "excluir"]);
            Route::get("/admin/leads/exportar", [LeadsController::class, "exportarLeads"]);
            // LEADS FRIAS
            Route::post("/admin/leads/frias/importar", [LeadsFriasController::class, "importarLeads"]);
            //saldos
            Route::post("/admin/saldos/{user}/novo", [UserController::class, "novoSaldo"]);
            Route::post("/admin/saldos/reposicao/atualizar", [UserController::class, "novoSaldoReposicao"]);
            //vendas
            Route::get("/admin/financeiro/vendas", [FinanceiroController::class, "extratos"]);
            // Avisos
            Route::get("/admin/avisos", [AvisosController::class, "index"]);
            Route::post("/admin/avisos", [AvisosController::class, "save"]);
            // Notificações
            Route::get("/admin/notificacoes", [NotificacoesPagamentoController::class, "index"]);

            // Reposições
            Route::get("/admin/reposicoes", [ReposicaoController::class, "indexAdmin"]);
            Route::post("/admin/reposicoes/rejeitar", [ReposicaoController::class, "rejeitar"]);
            Route::post("/admin/reposicoes/aprovar", [ReposicaoController::class, "aprovar"]);

            // MARKETING
            Route::get("/admin/marketing", [MarketingController::class, "index"]);
            Route::post("/admin/marketing/email", [MarketingController::class, "startEmail"]);
            // MARKETING MOBILE
            Route::get("/admin/marketing/mobile", [MarketingController::class, "indexApp"]);
            Route::post("/admin/marketing/mobile", [MarketingController::class, "startMobile"]);

            // TEMPLATE
            Route::get("/admin/marketing/templates", [TemplateEmailController::class, "index"]);
            Route::get("/admin/template/novo", [TemplateEmailController::class, "novoTemplate"]);
            Route::get("/admin/template/editar/{id}", [TemplateEmailController::class, "editarTemplate"]);
            Route::get("/admin/template/apagar/{id}", [TemplateEmailController::class, "apagarTemplate"]);
            Route::get("/admin/template/get/{id}", [TemplateEmailController::class, "getTemplate"]);
            Route::post("/admin/template/salvar/{id?}", [TemplateEmailController::class, "salvar"]);
            
            Route::get("/admin/pacotes", [PacotesController::class, "adminIndexView"]);
            Route::get("/admin/pacotes/novo", [PacotesController::class, "novoPacote"]);
            Route::get("/admin/pacotes/editar/{id}", [PacotesController::class, "editarPacote"]);
            Route::post("/admin/pacotes/salvar/{id?}", [PacotesController::class, "salvarPacote"]);

        });
        Route::middleware(["roleValidate:admin,user"])->group(function() {
            //perfil
            Route::get("/profile", [ProfileController::class, "index"]);
            //Leads
            Route::get("/leads", [LeadsController::class, "index"]);
            Route::get("/leads/search", [LeadsController::class, "search"]);

            //Leads frias
            // Route::get("/leads/frias", [LeadsFriasController::class, "index"]);
            // Route::get("/leads/frias/search", [LeadsFriasController::class, "search"]);

            //comprar leads
            Route::get("/leads/comprar/{id_lead}", [LeadsController::class, "comprarLeadModal"]);
            Route::post("/leads/comprar/{id_lead}", [LeadsController::class, "comprarLead"]);

            //comprar leads frias
            Route::get("/leads/frias/comprar/{id_lead}", [LeadsFriasController::class, "comprarLeadModal"]);
            Route::post("/leads/frias/comprar/{id_lead}", [LeadsFriasController::class, "comprarLead"]);


            // Comprar pacotes
            Route::get("/pacotes", [PacotesController::class, "pacotesCorretor"]);
            Route::post("/pacotes/comprar", [PacotesController::class, "comprarPacote"]);


            //pagamento
            Route::post("/pagamento/saldo/add", [MercadoPagoController::class, "addSaldo"]);
            Route::get("/saldo/info", function() {
                return Auth::user()->getValorSaldo(true);
            }); 
            
            // Meus extratos
            Route::get("/meus/extratos", [FinanceiroController::class, "meusExtrato"]);
            // Corretores
            Route::get("/corretores", [CorretoresController::class, "index"]);
            Route::get("/corretores/novo", [CorretoresController::class, "novoView"]);
            Route::get("/corretores/editar/{id}", [CorretoresController::class, "editarView"]);
            Route::post("/corretor/salvar/{id?}", [CorretoresController::class, "salvar"]);
            // Enviar Lead
            Route::get("modal/enviar/lead/{lead_id}", [MinhasLeadsController::class, "modalEnviarLead"]);
            Route::post("enviar/lead", [MinhasLeadsController::class, "enviarLead"]);
            //Remover lead enviada
            Route::get("/remover/lead/{id_lead}/{id_user}", [MinhasLeadsController::class, "remover"]);
            // Checkout
            Route::get("/checkout", [CheckoutController::class, "index"]);
        });

    // Minhas leads
    Route::get("/minhas/leads", [MinhasLeadsController::class, "index"]);
    Route::get("/minhas/leads/export/excel", [MinhasLeadsController::class, "excelDownload"]);
    //reposição
    Route::get("/reposicoes", [ReposicaoController::class, "index"]);
    Route::post("/reposicao/nova", [ReposicaoController::class, "gerarReposicao"]);
});

    // Importador (desenvolvimento, apenas)
    Route::get("/importar", function() {
        return view("pages.importadores.importador");
    });
    Route::post("/importar", [Importadores::class,"importarCsv"]);
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\EmailMarketing;
use App\Models\TemplateEmail;
use App\Models\TokensMobile;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Faker\Test\Provider\UserAgentTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PhpParser\Parser\Tokens;

class MarketingController extends Controller
{
    public function index() {
        $templates = TemplateEmail::all();
        $users = User::where("role", "user")->get();
        return view("pages.marketing_form", compact(
            "templates",
            "users"
        ));
    }
    public function indexApp() {
        $tokens = TokensMobile::all();
        return view("pages.mobileMarketing",
            [
                "dipositivos" => $tokens->count(),
                "tokens" => $tokens
            ]
        );
    }
    public function startEmail(Request $request) {
        set_time_limit(90000);
        $emails = [];
        if($request->filtro === "users") {
            $users = $request->users;
            $emails = User::whereIn("id",$users)->get()->pluck("email")->toArray();
        }
        if($request->filtro === "ddd") {
            $emails = User::where("role", "user")
                ->whereIn(DB::raw("SUBSTRING(telefone, 2, 2)"), $request->ddds)
                ->get()
                ->pluck("email")
                ->toArray();
        }
        if($request->filtro === "users_month") {
            //usuários a um mês sem colocar saldo
            $dataLimite = Carbon::now()->subMonth(); // Data atual menos um mês

            $usuariosSemSaldo = User::where("role", "user")
                ->whereDoesntHave('saldos', function ($query) use ($dataLimite) {
                $query->where('tipo', 'entrada')
                      ->where('created_at', '>=', $dataLimite);
            })->get()->pluck("email")->toArray();
            $emails = $usuariosSemSaldo;
        }
        if($request->filtro === "users_all") {
            //usuários que nunca colocaram saldo
            $emails = User::whereDoesntHave('saldos')
                ->get()
                ->pluck("email")
                ->toArray();
        }

        $assunto = $request->assunto;
        $emails_array = array_chunk($emails, 20);
        foreach($emails_array as $email_array) {
            try {
                Mail::to("contato@indicasaude.com.br")
                ->bcc($email_array)
                ->send(new EmailMarketing($assunto, $request->template)); 
                sleep(1);   
            } catch(Exception $e) {
                // 
            }
        }
        return response()->json([
            "erro" => false,
            "msg" => "E-mails enviado com sucesso!"
        ]);
    }       
    public function startMobile(Request $request) {
        $title = $request->title ?? "";
        $msg = $request->msg ?? "";
        $tokens = TokensMobile::all()->pluck("token")->toArray();

        $data = [];

        foreach($tokens as $token) {
            array_push($data, [
                "to" => $token,
                "title" => $title,
                "body" => $msg
            ]);
        }

        $curl = curl_init();
        
        curl_setopt_array($curl, [
          CURLOPT_URL => "https://exp.host/--/api/v2/push/send",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($data),
          CURLOPT_HTTPHEADER => [
            "Content-Type: application/json"
          ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            return response(["erro" => true]);
        } else {
            return response(["erro" => false, "response" => $response, "data" => $data]);
        }
    }
}

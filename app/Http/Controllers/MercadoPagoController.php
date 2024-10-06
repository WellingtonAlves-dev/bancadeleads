<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NotificacoesPagamento;
use App\Models\Saldos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MercadoPagoController extends Controller
{
    public function __construct() {
        \MercadoPago\SDK::setAccessToken(env("MERCADOPAGO_PRIVATE_KEY"));    
    }

    public function addSaldo(Request $request) {
        $valor = $request->valor;
        if(!$valor) {
            return abort(404, "O campo valor é obrigatório");
        }
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", ".", $valor);
        if(!is_numeric($valor)) {
            return abort(500, "O campo valor precisa ser numerico");
        }
        $valor = floatval($valor);

        $preferences = new \MercadoPago\Preference();

        $item = new \MercadoPago\Item();
        $item->title = "SALDO ". strtoupper(env("APP_NAME"));
        $item->quantity = 1;
        $item->unit_price = $valor;

        $unique_key = time() . "id" . Auth::user()->id;

        $preferences->notification_url = url("api/notificacao/mercadopago/".$unique_key);
        $preferences->back_urls = array(
            "success" => url("api/backurl/".$unique_key)
        );
        $preferences->auto_return = "approved";
        // $preferences->notification_url = "https://449c-2804-1b3-ab03-2f3e-e13e-973b-7e12-5399.ngrok-free.app/api/notificacao/mercadopago/".$unique_key;
        $preferences->items = array($item);
        if($preferences->save()) {
            NotificacoesPagamento::create([
                "user_id" => Auth::user()->id,
                "preference_id" => $preferences->id,
                "unique_key" => $unique_key
            ]);
            return $preferences->init_point;
        }
        return abort(500, "Não foi possível gerar uma preferencia");
    }
    
    public function backUrls($unique_key, Request $request, Saldos $saldos) {
        $merchant_order = $request->get("merchant_order_id");
        $paymentId = $request->get("payment_id");
        $status = $request->get("status");
        if($status === "approved") {
            $merchant_order = \MercadoPago\MerchantOrder::find_by_id($paymentId);
            $paid_amount = 0;
            foreach ($merchant_order->payments as $payment) {  
                if ($payment['status'] == 'approved'){
                    $paid_amount += $payment['transaction_amount'];
                }
            }
           
            // If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
            if($paid_amount >= $merchant_order->total_amount){
                if (count($merchant_order->shipments)>0) { // The merchant_order has shipments
                    if($merchant_order->shipments[0]->status == "ready_to_ship") {
                        $notificacoes = NotificacoesPagamento::where("unique_key", $unique_key)->first();
                        $notificacoes->status = $merchant_order->shipments[0]->status;
                        $notificacoes->topic = $request->get("type");
                        $notificacoes->id_payment = $paymentId;
                        $notificacoes->amount = $paid_amount;
                        $notificacoes->save();
                        $saldos->gerarEntrada($notificacoes->user_id, $paid_amount, "COMPRA APROVADA #" . $paymentId);
                    }
                }
            } else {
                abort(500, "pagamento não aprovado!");
            }
        }
        return redirect("/");
    }

    public function notificacao($unique_key, Request $request, Saldos $saldos) {
        $merchant_order = null;
        $paymentId = $request->get("data_id");
        $payment = null;
        switch($request->get("type")) {
            case "payment":
                $payment = \MercadoPago\Payment::find_by_id($paymentId);
                // Get the payment and the corresponding merchant_order reported by the IPN.
                $merchant_order = \MercadoPago\MerchantOrder::find_by_id($payment->order->id);
                break;
            case "merchant_order":
                $merchant_order = \MercadoPago\MerchantOrder::find_by_id($paymentId);
                break;
        }
      
        if($payment) {
            $notificacoes = NotificacoesPagamento::where("unique_key", $unique_key)->first();   
            if($notificacoes->status == "approved") {
                return "Pagamento já aprovado";
            }
            $notificacoes->status = $payment->status;
            $notificacoes->topic = $request->get("type");
            $notificacoes->id_payment = $paymentId;
            $notificacoes->amount = $payment->transaction_amount;
            $notificacoes->save();

            if($payment->status == "approved") {
                $saldos->gerarEntrada($notificacoes->user_id, $payment->transaction_amount, "COMPRA APROVADA #" . $paymentId);
            } else {
                // Não sei
            }
        } else {

            $paid_amount = 0;
            foreach ($merchant_order->payments as $payment) {  
                if ($payment['status'] == 'approved'){
                    $paid_amount += $payment['transaction_amount'];
                }
            }
           
            // If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
            if($paid_amount >= $merchant_order->total_amount){
                if (count($merchant_order->shipments)>0) { // The merchant_order has shipments
                    if($merchant_order->shipments[0]->status == "ready_to_ship") {
                        $notificacoes = NotificacoesPagamento::where("unique_key", $unique_key)->first();
                        $notificacoes->status = $merchant_order->shipments[0]->status;
                        $notificacoes->topic = $request->get("type");
                        $notificacoes->id_payment = $paymentId;
                        $notificacoes->amount = $paid_amount;
                        $notificacoes->save();
                        $saldos->gerarEntrada($notificacoes->user_id, $paid_amount, "COMPRA APROVADA #" . $paymentId);
                    }
                }
            } else {
                abort(500, "pagamento não permitido!");
            }
        }
    }
}

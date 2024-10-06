<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TemplateEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TemplateEmailController extends Controller
{
    public function index() {
        $templates = TemplateEmail::all();
        return view("pages.templates_marketing", compact("templates"));
    }
    public function novoTemplate() {
        return view("pages.formTemplateEmail");
    }
    public function editarTemplate($id) {
        $template = TemplateEmail::findOrFail($id);
        return view("pages.formTemplateEmail", compact("template"));
    }   
    public function getTemplate($id) {
        $template = TemplateEmail::findOrFail($id);
        return response()->json($template);
    }
    public function salvar(Request $request, $id = null) {
        $rules = [];
        if($id) {
            $rules = [
                "title" => "",
                "model" => "required"
            ];
        } else {
            $rules = [
                "title" => "required",
                "model" => "required"
            ];
        }
        $data = [];
        if($request->no_redirect) {
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()) {
                return response()->json(["erro" => true, "msg" => $validator->messages()]);
            }
            $data = $validator->valid();
            unset($data["_token"]);
            unset($data["no_redirect"]);
        } else {
            $data = $request->validate($rules);
        }
        $msg = "";
        $template = null;
        $idRedirect = null;
        if($id) {
            $template = TemplateEmail::where("id", $id)->update($data);
            $msg = "Template atualizada com sucesso";
            $idRedirect = $id;
        } else {
            $template = TemplateEmail::create($data);
            $msg = "Template criada com sucesso";
            $idRedirect = $template->id;
        }
        if($request->no_redirect) {
            return response()->json(["msg" => $msg, "template" => $template]);
        }
        return redirect("admin/template/editar/".$idRedirect)->with("success_save", $msg);
    }
    public function apagarTemplate($id) {
        TemplateEmail::where("id", $id)->delete();
        return redirect("admin/marketing/templates")->with("success_save", "Template apagada com sucesso");
    }
}

@extends("templates.list")
@section("title")
Carteira de Leads
@endsection
@section("menu")
<button type="button" onclick="$('#exportar_modal').modal('show')" class="btn btn-success float-right">
    Exportar Leads
    <i class="fas fa-file-excel"></i>
</button>
@endsection
@section("tabela")
<table class="table table-bordered" style="font-size: 11px" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>ID LEAD</th>
            @if(!Auth::user()->vinculado)
                <th>PREÇO</th>
            @endif
            <th>PERFIL</th>
            {{-- <th>DDD</th> --}}
            <th>TELEFONE</th>
            <th>E-MAIL</th>
            <th>NOME</th>
            <th>PLANO</th>
            <th>TIPO</th>
            <th>AQUISIÇÃO</th>
            <th>CORRETOR</th>
            <th>EXTRA</th>
            {{-- @if(!Auth::user()->vinculado)
                <th>LEAD FRIA</th>
            @endif --}}
            <th><!-- REPOSIÇÃO --></th>
            <th><!-- ENVIAR LEAD --></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>ID LEAD</th>
            @if(!Auth::user()->vinculado)
                <th>PREÇO</th>
            @endif
            <th>PERFIL</th>
            {{-- <th>DDD</th> --}}
            <th>TELEFONE</th>
            <th>E-MAIL</th>
            <th>NOME</th>
            <th>PLANO</th>
            <th>TIPO</th>
            <th>AQUISIÇÃO</th>
            <th>CORRETOR</th>
            <th>EXTRA</th>
            {{-- @if(!Auth::user()->vinculado)
                <th>LEAD FRIA</th>
            @endif --}}
            <th><!-- REPOSIÇÃO --></th>
            <th><!-- ENVIAR LEAD --></th>
        </tr>
    </tfoot>
    <tbody>
        @foreach ($leads as $key => $lead)
            @php
                if($lead->reposicaoID && $lead->reposicaoStatus === "aprovada") {
                    continue;
                }
            @endphp
            <tr>
                <td>{{$lead->id}}</td>
                @if(!Auth::user()->vinculado)
                    <td>R$ {{ number_format($lead->preco, 2, ",", ".") }}</td>
                @endif
                <td>{{$lead->idade}}</td>
                {{-- <td>{{$lead->ddd}}</td> --}}
                <td>({{$lead->ddd}}) {{$lead->telefone}}</td>
                <td>{{$lead->email}}</td>
                <td>{{$lead->nome_lead}}</td>
                <td>{{$lead->plano->nome}}</td>
                <td>{{$lead->tipo->nome}}</td>
                <td>{{date("d/m/Y H:m:s", strtotime($lead->dataAquisicao))}}</td>
                <td id="tr_{{$key}}">
                    {{-- verificar se lead tem corretor --}}
                    @if($lead->corretor)
                        <a href="{{url("corretores/editar/".$lead->corretor_id)}}" target="_blank">{{ $lead->corretor }}</a>
                    @else
                        Lead sem corretor
                    @endif
                </td>
                <td>
                    {{$lead->extra ?? ""}}
                </td>
                {{-- @if(!Auth::user()->vinculado)
                    <td>
                            {{($lead->statusLeadFria ?? false) ? "SIM" : "NÃO" }}
                    </td>
                @endif --}}
                <td>
                    @if(Auth::user()->role == "user")
                        @php
                            $blockedReposicao = strtotime(date("Y-m-d H:i:s")) > strtotime($lead->dataAquisicao . " + 48 hours");
                        @endphp
                        @if($lead->reposicaoID)
                            <button 
                                onclick="alert('Contestação já solicitada para esta Lead')"
                                style="font-size: 10px;" class="btn btn-sm btn-secondary">
                                Contestar
                            </button>
                        @elseif($lead->preco > ($saldoReposicao ?? 0))
                            <button 
                                onclick="alert('Limite de contestação excedido')"
                                style="font-size: 10px;" class="btn btn-sm btn-secondary">
                                Contestar
                            </button>
                        @elseif($blockedReposicao)
                            <button 
                                onclick="alert('Já expirou o tempo para solicitar a contestação desta Lead')"
                                style="font-size: 10px;" class="btn btn-sm btn-secondary">
                                Contestar
                            </button>
                        @elseif(($lead->statusLeadFria ?? false))
                            <button 
                                onclick="alert('Não é possível solicitar contestação de leads frias')"
                                style="font-size: 10px;" class="btn btn-sm btn-secondary">
                                Contestar
                            </button>
                        @else
                            <span class="d-inline-block" data-toggle="popover" data-content="Disabled popover">
                                <button 
                                    onclick="modalReposicao('{{$lead->id}}')"
                                    style="font-size: 10px;" class="btn btn-sm btn-secondary" {{$blockedReposicao ? "disabled" : ""}}>
                                    Contestar
                                </button>
                            </span>
                        @endif
                    @else
                        <small>Somente o usuário que comprou a lead pode solicitar a contestação</small>
                    @endif
                </td>
                <th>
                    @if(Auth::user()->role == "user")
                        <button class="btn btn-sm btn-primary" onclick="{{$lead->corretor ? "alert('Esta lead já foi atribuída a um corretor. Se você enviar para outro corretor, ela será removida do corretor atual.');" : ""}}enviarLeadModal('{{$lead->id}}', '{{$key}}')" style="font-size: 10px;">Enviar P/ Corretor</button>
                    @endif
                </th>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{$paginador->onEachSide(0)->withQueryString()->links()}}
</div>

<div style="color: black" class="modal" id="exportar_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Exportar Leads</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Período inicial</label>
            <input type="date" class="form-control" id="data_inicial">
            <span class="text-danger" id="data_inicial_alert"></span>
          </div>
          <div class="form-group">
            <label>Período Final</label>
            <input type="date" class="form-control" id="data_final">
            <span class="text-danger" id="data_final_alert"></span>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" onclick="exportarLeads()">Exportar</button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal" id="motivo_reposicao" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="color: black">Motivo da contestação</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="color: black">
            <p>
                Informe detalhadamente o motivo da sua contestação e clique em solicitar
            </p>
            <textarea class="form-control" id="descricao_reposicao"></textarea>
            <span class="text-danger" id="alerta_motivo_reposicao"></span>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="solicitarReposicao()">Solicitar</button>
          <button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
  

@endsection
@section("script")
<script>
    var leadReposicaoSelecionada = null;
    let trAtual = 0;
    function enviarLeadModal(lead_id, tr_key) {
        MODAL_LOADING.modal("show");
        $.ajax({
            method: "GET",
            url: "{{url("modal/enviar/lead")}}" + "/" + lead_id,
            success: (res) => {
                trAtual = tr_key;
                MODAL_LOADING.modal("hide");
                DIV_GENERIC.html(res);
                $("#modal_enviar_lead").modal("show");
            }
        })
    }
    function enviarLead() {
        MODAL_LOADING.modal("show");
        $.ajax({
            method: "POST",
            url: "{{url("enviar/lead")}}",
            data: {
                lead: $("#lead_id_enviar").val(),
                corretor: $("#userSendLead").val(),
                "_token": "{{csrf_token()}}"
            },
            success: (res) => {
                $("#modal_enviar_lead").modal("hide");
                MODAL_LOADING.modal("hide");

                DIV_GENERIC.html(res);

                $("#tr_"+trAtual).html(`
                    <a target="_blank" href='{{url("corretores/editar")}}/${$("#corretor_id_alert").val()}'>${$("#corretor_alert").val()}</a>
                `);

                $("#modal_alert").modal("show");
            },
            error: (err, xhr) => {
                $("#modal_enviar_lead").modal("hide");
                MODAL_LOADING.modal("hide");
                
                DIV_GENERIC.html(err.responseText);
                $("#modal_alert").modal("show");
            }
        })
    }

    function removerLead(id_lead, id_user) {
        $.ajax({
            url: "{{url("remover/lead")}}" + `/${id_lead}/${id_user}`,
            success: res => {
                console.log(res);
                if(res.msg === "success") {
                    $("#tr_"+id_lead+"_"+id_user).remove();
                } else {
                    alert("Não foi possível remover a lead");
                }
            }
        });
    }

    function exportarLeads() {
        const URL = "{{url("minhas/leads/export/excel")}}";
        let data_inicial = $("#data_inicial");
        let data_final = $("#data_final");
        let erros = 0;

        if(data_inicial.val().length === 0) {
            $("#data_inicial_alert").html("O campo período inicial é obrigatório");
            data_inicial.addClass("is-invalid");
            erros++;
        } else {
            console.log("valido");
            $("#data_inicial_alert").html("");
            data_inicial.removeClass("is-invalid");
        }

        if(data_final.val().length === 0) {
            $("#data_final_alert").html("O campo período inicial é obrigatório");
            data_final.addClass("is-invalid");
            erros++;
        } else {
            $("#data_final_alert").html("");
            data_final.removeClass("is-invalid");
        }

        if(erros === 0) {
            console.log("aqui!");
            const params = new URLSearchParams({
                data_inicial: data_inicial.val(),
                data_final: data_final.val(),
            });

            window.location.href = URL + "?" + params;

        }
    }

    function modalReposicao(lead_id) {
        leadReposicaoSelecionada = lead_id;
        $("#motivo_reposicao").modal("show");
    }

    function solicitarReposicao() {
        let descricao = $("#descricao_reposicao")
        if(descricao.val().length === 0) {
            descricao.addClass("is-invalid");
            $("#alerta_motivo_reposicao").html("É necessário descrever o motivo da contestação")
            return;
        } else {
            $("#alerta_motivo_reposicao").html("")
            descricao.removeClass("is-invalid");
        }

        MODAL_LOADING.modal("show");
        $.ajax({
            method: "POST",
            url: "{{url("reposicao/nova")}}",
            data: {
                id_lead: leadReposicaoSelecionada,
                descricao: descricao.val(),
                "_token": "{{csrf_token()}}"
            },
            success: function(res) {
                MODAL_LOADING.modal("hide");
                $("#motivo_reposicao").modal("hide");
                descricao.val("");
                alert(res.msg);
                window.location.href = window.location.href;
            },
            error: () => MODAL_LOADING.modal("hide")
        })
    }

</script>
@endsection
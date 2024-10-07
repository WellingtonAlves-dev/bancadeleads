@extends("templates.list")
@section("title")
<div class="d-flex justify-content-between mb-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Gerenciamento de Leads</a></li>
          <li class="breadcrumb-item active" aria-current="page">Minhas Leads</li>
        </ol>
    </nav>
</div>
@endsection
@section("menu")
<button type="button" onclick="$('#exportar_modal').modal('show')" class="btn btn-success float-right">
    Exportar Leads
    <i class="fas fa-file-excel"></i>
</button>
@endsection
@section("tabela")
{{-- <table class="table table-bordered" style="font-size: 12px" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            @if(!Auth::user()->vinculado)
                <th>Valor</th>
            @endif
            <th>Idade</th>
            <th>Telefone</th>
            <th>E-mail</th>
            <th>Nome</th>
            <th>Plano</th>
            <th>Categoria</th>
            <th>Data de Aquisição</th>
            <th>Corretor</th>
            <th>Informações Adicionais</th>
            <th>Contestar</th>
            <th>Ação</th>
        </tr>
    </thead>
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
                <td>({{$lead->ddd}}) {{$lead->telefone}}</td>
                <td>{{$lead->email}}</td>
                <td>{{$lead->nome_lead}}</td>
                <td>{{$lead->plano->nome}}</td>
                <td>{{$lead->tipo->nome}}</td>
                <td>{{date("d/m/Y H:i:s", strtotime($lead->dataAquisicao))}}</td>
                <td id="tr_{{$key}}">
                    @if($lead->corretor)
                        <a href="{{url("corretores/editar/".$lead->corretor_id)}}" target="_blank">{{ $lead->corretor }}</a>
                    @else
                        Sem corretor
                    @endif
                </td>
                <td>
                    {{$lead->extra ?? ""}}
                </td>
                <td>
                    @if(Auth::user()->role == "user")
                        @php
                            $blockedReposicao = strtotime(date("Y-m-d H:i:s")) > strtotime($lead->dataAquisicao . " + 48 hours");
                        @endphp
                        @if($lead->reposicaoID)
                            <button 
                                onclick="alert('Contestação já solicitada')"
                                style="font-size: 10px;" class="btn btn-sm btn-secondary">
                                Contestar
                            </button>
                        @elseif($lead->preco > ($saldoReposicao ?? 0))
                            <button 
                                onclick="alert('Limite excedido')"
                                style="font-size: 10px;" class="btn btn-sm btn-secondary">
                                Contestar
                            </button>
                        @elseif($blockedReposicao)
                            <button 
                                onclick="alert('Prazo expirado')"
                                style="font-size: 10px;" class="btn btn-sm btn-secondary">
                                Contestar
                            </button>
                        @elseif(($lead->statusLeadFria ?? false))
                            <button 
                                onclick="alert('Leads frias não são contestáveis')"
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
                        <small>Somente o comprador pode contestar</small>
                    @endif
                </td>
                <th>
                    @if(Auth::user()->role == "user")
                        <button class="btn btn-sm btn-primary" onclick="{{$lead->corretor ? "alert('Esta lead já está com um corretor.')" : ""}}enviarLeadModal('{{$lead->id}}', '{{$key}}')" style="font-size: 10px;">Enviar para Corretor</button>
                    @endif
                </th>
            </tr>
        @endforeach
    </tbody>
</table> --}}
<div class="row">
    @foreach ($leads as $key => $lead)
        @php
            if($lead->reposicaoID && $lead->reposicaoStatus === "aprovada") {
                continue;
            }
        @endphp
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="d-flex align-items-center p-3">
                    <!-- Imagem ajustada ao lado esquerdo -->
                    <img src="{{url('storage/'.$lead->plano->logo)}}" style="height: 80px; width: 80px; object-fit: cover;" class="me-3 mr-3" alt="Lead Image">
                    
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-1">ID: {{$lead->id}}</h5>
                        @if(!Auth::user()->vinculado)
                            <p class="card-text mb-1">Valor: R$ {{ number_format($lead->preco, 2, ",", ".") }}</p>
                        @endif
                        <p class="card-text mb-1">Idade: {{$lead->idade}}</p>
                        <p class="card-text mb-1">Telefone: ({{$lead->ddd}}) {{$lead->telefone}}</p>
                        <p class="card-text mb-1">E-mail: {{$lead->email}}</p>
                        <p class="card-text mb-1">Nome: {{$lead->nome_lead}}</p>
                        <p class="card-text mb-1">Plano: {{$lead->plano->nome}}</p>
                        <p class="card-text mb-1">Categoria: {{$lead->tipo->nome}}</p>
                        <p class="card-text mb-1">Data de Aquisição: {{date("d/m/Y H:i:s", strtotime($lead->dataAquisicao))}}</p>
                        <p class="card-text mb-1">
                            Corretor:
                            @if($lead->corretor)
                                <span class="badge badge-warning">
                                    <a href="{{url("corretores/editar/".$lead->corretor_id)}}" target="_blank">{{ $lead->corretor }}</a>
                                </span>
                            @else
                                Sem corretor
                            @endif
                        </p>
                        <p class="card-text mb-1">Informações Adicionais: {{$lead->extra ?? ""}}</p>
                    </div>
                </div>
                
                <!-- Botões de ação no rodapé do card -->
                <div class="card-footer d-flex justify-content-between align-items-center">
                    @if(Auth::user()->role == "user")
                        @php
                            $blockedReposicao = strtotime(date("Y-m-d H:i:s")) > strtotime($lead->dataAquisicao . " + 48 hours");
                        @endphp
                
                        <div class="badges">
                            <!-- Verificação de mensagens para os badges -->
                            @if($lead->reposicaoID)
                                <span class="badge bg-warning text-dark me-2">Contestação já solicitada</span>
                            @elseif($lead->preco > ($saldoReposicao ?? 0))
                                <span class="badge bg-danger me-2">Limite excedido</span>
                            @elseif($blockedReposicao)
                                <span class="badge bg-secondary me-2">Prazo expirado</span>
                            @elseif(($lead->statusLeadFria ?? false))
                                <span class="badge bg-info me-2">Leads frias não são contestáveis</span>
                            @endif
                        </div>
                
                        <!-- Botão de contestar com base nas condições -->
                        <div>
                            @if($lead->reposicaoID)
                                <button class="btn btn-sm btn-secondary" disabled>Contestar</button>
                            @elseif($lead->preco > ($saldoReposicao ?? 0))
                                <button class="btn btn-sm btn-secondary" disabled>Contestar</button>
                            @elseif($blockedReposicao)
                                <button class="btn btn-sm btn-secondary" disabled>Contestar</button>
                            @elseif(($lead->statusLeadFria ?? false))
                                <button class="btn btn-sm btn-secondary" disabled>Contestar</button>
                            @else
                                <button class="btn btn-sm btn-secondary" onclick="modalReposicao('{{$lead->id}}')" {{$blockedReposicao ? "disabled" : ""}}>Contestar</button>
                            @endif
                        </div>
                    @else
                        <small>Somente o comprador pode contestar</small>
                    @endif
                </div>

                <div class="card-footer">
                    @if(Auth::user()->role == "user")
                        <button class="btn btn-sm btn-primary" onclick="{{$lead->corretor ? "alert('Esta lead já pertence a um corretor.')" : ""}};enviarLeadModal('{{$lead->id}}', '{{$key}}')">Enviar para Corretor</button>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>


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
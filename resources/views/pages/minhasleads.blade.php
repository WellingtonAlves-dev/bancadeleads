@extends("templates.list")
@section("title")
<!-- Título da página -->
<div class="d-flex justify-content-between mb-2">
    <h2>Minhas Leads</h2>
</div>
@endsection
@section("menu")
<!-- Botão de exportar leads -->
<button type="button" onclick="$('#exportar_modal').modal('show')" class="btn btn-success float-right">
    Exportar Leads <i class="fas fa-file-excel"></i>
</button>
@endsection
@section("tabela")
<!-- Listagem de leads em cards compactos -->
<div class="row">
    @foreach ($leads as $key => $lead)
        @php
            if($lead->reposicaoID && $lead->reposicaoStatus === "aprovada") {
                continue;
            }
        @endphp
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white p-2">
                    <h6 class="card-title mb-0">Lead #{{$lead->id}}</h6>
                </div>
                <div class="card-body p-2">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item p-1"><small><strong>Data:</strong> {{date("d/m/Y H:i:s", strtotime($lead->dataAquisicao))}}</small></li>
                        @if(!Auth::user()->vinculado)
                            <li class="list-group-item p-1"><small><strong>Valor:</strong> R$ {{ number_format($lead->preco, 2, ",", ".") }}</small></li>
                        @endif
                        <li class="list-group-item p-1"><small><strong>Categoria:</strong> {{$lead->tipo->nome}}</small></li>
                        <li class="list-group-item p-1"><small><strong>Nome:</strong> {{$lead->nome_lead}}</small></li>
                        <li class="list-group-item p-1"><small><strong>E-mail:</strong> {{$lead->email}}</small></li>
                        <li class="list-group-item p-1"><small><strong>Telefone:</strong> ({{$lead->ddd}}) {{$lead->telefone}}</small></li>
                        <li class="list-group-item p-1"><small><strong>Idade:</strong> {{$lead->idade}}</small></li>
                        <li class="list-group-item p-1"><small><strong>Plano:</strong> {{$lead->plano->nome}}</small></li>
                        <li class="list-group-item p-1"><small><strong>Info:</strong> {{$lead->extra ?? "Nenhuma"}}</small></li>
                        <li class="list-group-item p-1">
                            <small>
                                <strong>Corretor:</strong>
                                @if($lead->corretor)
                                    <span class="badge badge-warning">
                                        <a href="{{url("corretores/editar/".$lead->corretor_id)}}" target="_blank">{{ $lead->corretor }}</a>
                                    </span>
                                @else
                                    <span class="badge badge-secondary">Sem corretor</span>
                                @endif
                            </small>
                        </li>
                    </ul>
                </div>
                <div class="card-footer p-2">
                    @if(Auth::user()->role == "user")
                        @php
                            $blockedReposicao = strtotime(date("Y-m-d H:i:s")) > strtotime($lead->dataAquisicao . " + 48 hours");
                        @endphp
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Reposição: </strong>
                                @if($lead->reposicaoID)
                                    <span class="badge bg-warning text-dark">Contestação já solicitada</span>
                                @elseif($lead->preco > ($saldoReposicao ?? 0))
                                    <span class="badge bg-danger">Limite excedido</span>
                                @elseif($blockedReposicao)
                                    <span class="badge bg-danger">Prazo expirado</span>
                                @elseif(($lead->statusLeadFria ?? false))
                                    <span class="badge bg-info">Lead fria</span>
                                @else
                                    <button class="btn btn-sm btn-secondary p-1" onclick="modalReposicao('{{$lead->id}}')"><small>Clique aqui para Solicitar reposição desta Lead</small></button>
                                @endif
                            </div>
                        </div>
                    @else
                        <small class="text-muted">Somente o comprador pode contestar</small>
                    @endif
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button class="btn w-100 bg-danger text-white p-2 mt-3" onclick="{{$lead->corretor ? "alert('Esta lead já pertence a um corretor.')" : ""}};enviarLeadModal('{{$lead->id}}', '{{$key}}')"><small>Enviar Lead para corretor</small></button>
            </div>
        </div>
    @endforeach
</div>

<!-- Paginação -->
<div class="d-flex justify-content-end mt-2">
    {{$paginador->onEachSide(0)->withQueryString()->links()}}
</div>

<!-- Modal de exportação -->
<div class="modal fade" id="exportar_modal" tabindex="-1" role="dialog">
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

<!-- Modal de contestação -->
<div class="modal fade" id="motivo_reposicao" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Solicitar contestação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Descreva com detalhes o motivo pela qual você está solicitando a contestação desta lead.</p>
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
        });
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
            $("#data_inicial_alert").html("");
            data_inicial.removeClass("is-invalid");
        }

        if(data_final.val().length === 0) {
            $("#data_final_alert").html("O campo período final é obrigatório");
            data_final.addClass("is-invalid");
            erros++;
        } else {
            $("#data_final_alert").html("");
            data_final.removeClass("is-invalid");
        }

        if(erros === 0) {
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
            $("#alerta_motivo_reposicao").html("É necessário descrever o motivo da contestação");
            return;
        } else {
            $("#alerta_motivo_reposicao").html("");
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
        });
    }
</script>
@endsection
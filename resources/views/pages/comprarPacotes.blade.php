@extends("templates.list")
@section("title")
    <div class="container py-4">
        <h1 class="display-4 text-center font-weight-bold text-gradient-primary">Escolha Seu Pacote de Leads</h1>
        <p class="text-center text-muted">Selecione o pacote que melhor atende às suas necessidades</p>
    </div>
@endsection
@section("tabela")
<style>
    .pricing-card {
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
    }

    .pricing-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .hover-scale:hover {
        transform: scale(1.03);
    }

    .lead-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid #e0e0e0;
        position: relative;
        overflow: hidden;
    }

    .lead-card.selected {
        border: 2px solid #4e73df;
        background-color: #f8fbff;
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.2);
    }

    .lead-card.selected::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background-color: #4e73df;
    }

    .lead-card .selected-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #4e73df;
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 12px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .lead-card.selected .selected-badge {
        opacity: 1;
    }

    .lead-card .check-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lead-card.selected .check-icon {
        background-color: #4e73df;
        color: white;
        border-color: #4e73df;
    }


    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .btn-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        border: none;
    }

    .text-gradient-primary {
        background: -webkit-linear-gradient(135deg, #4e73df, #224abe);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .lead-description ul {
        list-style-type: none;
        padding-left: 0;
    }

    .lead-description li {
        padding: 5px 0;
        position: relative;
        padding-left: 25px;
    }

    .lead-description li:before {
        content: "✓";
        color: #4e73df;
        position: absolute;
        left: 0;
    }

    .select2-container--default .select2-selection--multiple {
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        min-height: calc(1.5em + 1rem + 2px);
    }
</style>
    <div class="container py-5">
        <div class="row justify-content-center">
            @foreach($pacotes as $pacote)
                <div class="col-12 col-md-4 mb-4">
                    <div class="card pricing-card shadow-sm border-0 rounded-lg 
                            @if($loop->first) border-primary @endif
                            h-100 transition-all hover-scale">
                        <div class="card-header bg-white text-center py-4">
                            <h3 class="font-weight-bold text-primary">{{$pacote->name}}</h3>
                        </div>
                        <div class="card-body w-100">
                                {!! $pacote->descricao !!}
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center pb-4">
                            <button class="btn btn-lg btn-gradient-primary px-5 rounded-pill shadow-sm"
                                onclick="abrirModal({{$pacote->qtd_leads}}, {{$pacote->id}}, {{$pacote->qtd_desconto}})">
                                Selecionar
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- MODAL LEADS --}}
    <div class="modal fade" id="listagemLeads" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient-primary">
                    <h5 class="modal-title" style="color: black">Escolha <span id="quantidadeLeadsAescolher"
                            class="badge badge-light">0</span> LEADS</h5>
                    <button type="button" class="close text-white" onclick="fecharModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">DDD</label>
                                    <select onchange="filter()" class="form-control select2-multiple" id="ddd_choice"
                                        multiple="multiple">
                                        @for($i = 1; $i < 100; $i++)
                                            <option value="{{$i}}">DDD {{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tipo de Lead</label>
                                    <select onchange="filter()" class="form-control select2-multiple" id="tipo_choice"
                                        multiple="multiple">
                                        @foreach($tipos as $tipo)
                                            <option value="{{$tipo->id}}">{{$tipo->nome}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Plano</label>
                                    <select onchange="filter()" class="form-control select2-multiple" id="plano_choice"
                                        multiple="multiple">
                                        @foreach($planos as $plano)
                                            <option value="{{$plano->id}}">{{$plano->nome}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="position-relative" style="min-height: 400px;">
                            <div id="loading_spinner_lead" class="text-center py-5" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Carregando...</span>
                                </div>
                                <p class="mt-2">Buscando leads...</p>
                            </div>

                            <div class="row" id="card_container">
                                <!-- Cards serão inseridos aqui -->
                            </div>

                            <div class="text-center mt-4">
                                <button class="btn btn-outline-primary" onclick="carregarMaisLeads()" id="btnCarregarMais">
                                    <i class="fas fa-plus-circle mr-2"></i>Carregar mais leads
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <div class="mr-auto">
                        <div class="d-flex align-items-center mb-2">
                            <span class="font-weight-bold mr-2">Valor total:</span>
                            <span id="totalLeadsSelecionadas" class="h5 mb-0 text-dark">R$ 0,00</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <span class="font-weight-bold mr-2">Com desconto:</span>
                            <span id="totalLeadsSelecionadasDesconto" class="h4 mb-0 text-primary">R$ 0,00</span>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-piggy-bank mr-2"></i>
                            <span id="totalEconomia">Você economiza R$ 0,00</span>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="fecharModal()">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-success" onclick="finalizarPedido()" id="btnFinalizarPedido"
                        disabled>
                        <i class="fas fa-check-circle mr-2"></i>Finalizar Compra
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section("script")
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
        let limit = 20;
        var offset = 0;
        let LEADS_SELECIONADAS = [];
        let LIMITE_DE_LEADS_SELECIONADAS = 0;
        let ID_PACOTE;
        let TOTAL_DESCONTO = 0;
        const totalLeadPorPagina = 20;
        let MODAL_LISTAGEM_LEADS = $("#listagemLeads");

        function abrirModal(limite_leads, id_pacote, desconto) {
            LIMITE_DE_LEADS_SELECIONADAS = limite_leads;
            ID_PACOTE = id_pacote;
            TOTAL_DESCONTO = desconto;
            LEADS_SELECIONADAS = [];

            $("#quantidadeLeadsAescolher").text(LIMITE_DE_LEADS_SELECIONADAS);
            $("#quantidadeLeadsAescolher").removeClass("badge-danger badge-success").addClass("badge-light");

            filter();

            MODAL_LISTAGEM_LEADS.modal({
                backdrop: 'static',
                keyboard: false
            });
        }

        function fecharModal() {
            MODAL_LISTAGEM_LEADS.modal("hide");
        }

        function selecionarLead(id_lead, preco) {
            const leadElement = $(`#lead_${id_lead}`);
            const converterPreco = String(preco).replaceAll(".", "").replaceAll(",", ".");
            const novoPreco = Number(converterPreco);

            // Verificar se a lead já foi selecionada
            const leadIndex = LEADS_SELECIONADAS.findIndex(lead => lead.id == id_lead);

            if (leadIndex >= 0) {
                // REMOVER A LEAD
                LEADS_SELECIONADAS.splice(leadIndex, 1);
                leadElement.removeClass("selected");
            } else {
                // ADICIONAR A LEAD
                if (LEADS_SELECIONADAS.length < LIMITE_DE_LEADS_SELECIONADAS) {
                    LEADS_SELECIONADAS.push({
                        id: id_lead,
                        preco: novoPreco
                    });
                    leadElement.addClass("selected");
                } else {
                    alertify.warning(`Você só pode selecionar ${LIMITE_DE_LEADS_SELECIONADAS} leads neste pacote`);
                    return;
                }
            }

            atualizarContador();
            desabilitarButtons();
            gerarTotal();
        }

        function atualizarContador() {
            const restantes = LIMITE_DE_LEADS_SELECIONADAS - LEADS_SELECIONADAS.length;
            $("#quantidadeLeadsAescolher").text(restantes);

            // Mudar cor do badge conforme a quantidade
            const badge = $("#quantidadeLeadsAescolher");
            badge.removeClass("badge-danger badge-warning badge-success");

            if (restantes <= 0) {
                badge.addClass("badge-success");
            } else if (restantes <= Math.floor(LIMITE_DE_LEADS_SELECIONADAS / 2)) {
                badge.addClass("badge-warning");
            } else {
                badge.addClass("badge-danger");
            }
        }

        function desabilitarButtons() {
            const btnFinalizar = $("#btnFinalizarPedido");

            if (LEADS_SELECIONADAS.length === LIMITE_DE_LEADS_SELECIONADAS) {
                btnFinalizar.prop("disabled", false).removeClass("btn-secondary").addClass("btn-success");
            } else {
                btnFinalizar.prop("disabled", true).removeClass("btn-success").addClass("btn-secondary");
            }
        }

        function gerarTotal() {
            if (LEADS_SELECIONADAS.length === 0) {
                $("#totalLeadsSelecionadas").text("R$ 0,00");
                $("#totalLeadsSelecionadasDesconto").text("R$ 0,00");
                $("#totalEconomia").text("Você economiza R$ 0,00");
                return;
            }

            const total = LEADS_SELECIONADAS.reduce((acc, lead) => acc + lead.preco, 0);
            const desconto = (total * TOTAL_DESCONTO) / 100;
            const totalComDesconto = total - desconto;

            $("#totalLeadsSelecionadas").text(total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
            $("#totalLeadsSelecionadasDesconto").text(totalComDesconto.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
            $("#totalEconomia").text(`Você economiza ${desconto.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}`);
        }

        function filter(paginacao = false) {
            $("#card_container").css("opacity", "0.5");
            $("#loading_spinner_lead").show();
            $("#btnCarregarMais").prop("disabled", true);

            const ddd = $("#ddd_choice").val();
            const tipo = $("#tipo_choice").val();
            const plano = $("#plano_choice").val();

            if (!paginacao) {
                offset = 0;
            }

            $.ajax({
                method: "GET",
                url: "{{url('leads/search')}}",
                data: {
                    "selecionavel": "true",
                    ddd,
                    tipo,
                    plano,
                    offset,
                    limit,
                },
                success: function (res) {
                    $("#loading_spinner_lead").hide();
                    $("#card_container").css("opacity", "1");
                    $("#btnCarregarMais").prop("disabled", false);

                    if (res?.leads?.length > 0) {
                        let html = paginacao ? $("#card_container").html() : "";

                        res.leads.forEach(lead => {
                            const isSelected = LEADS_SELECIONADAS.some(l => l.id == lead.id);

                            html += `
        <div class="col-md-6 col-lg-4 mb-4">
            <div id="lead_${lead.id}" class="card lead-card h-100 ${isSelected ? 'selected' : ''}" 
                 onclick="selecionarLead('${lead.id}', '${lead.preco}')">
                <div class="check-icon">
                    <i class="fas fa-check" style="font-size: 12px; display: ${isSelected ? 'block' : 'none'};"></i>
                </div>
                <div class="selected-badge">
                    <i class="fas fa-check mr-1"></i>Selecionado
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">Lead #${lead.id}</h5>
                        <span class="badge badge-primary">${lead?.ddd || ''}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted"><i class="fas fa-tag mr-2"></i>${lead?.tipo?.nome || ''}</span>
                        <h4 class="text-primary mb-0">R$ ${lead?.preco || '0,00'}</h4>
                    </div>

                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-mobile-alt mr-2 text-muted"></i> ${lead?.plano?.nome || ''}</li>
                        <li class="mb-2"><i class="fas fa-user mr-2 text-muted"></i> Quantidade: ${lead?.idade || 'Não informada'}</li>
                        <li class="mb-2"><i class="fas fa-clock mr-2 text-muted"></i> Horário: ${lead?.horario_partida || 'Não informado'}</li>
                        <li class="mb-2"><i class="fas fa-city mr-2 text-muted"></i> DDD: ${lead?.ddd || 'Não informado'}</li>
                    </ul>
                </div>
            </div>
        </div>
    `;

                        });

                        $("#card_container").html(html);

                        if (!paginacao && res.leads.length < limit) {
                            $("#btnCarregarMais").hide();
                        } else {
                            $("#btnCarregarMais").show();
                        }
                    } else if (!paginacao) {
                        $("#card_container").html('<div class="col-12 text-center py-5"><i class="fas fa-info-circle fa-3x text-muted mb-3"></i><h4>Nenhum lead encontrado com esses filtros</h4></div>');
                        $("#btnCarregarMais").hide();
                    }

                    if (paginacao && res.leads.length < limit) {
                        $("#btnCarregarMais").hide();
                    }

                    atualizarContador();
                    desabilitarButtons();
                    gerarTotal();
                },
                error: function () {
                    $("#loading_spinner_lead").hide();
                    $("#card_container").css("opacity", "1");
                    $("#btnCarregarMais").prop("disabled", false);

                    if (!paginacao) {
                        $("#card_container").html('<div class="col-12 text-center py-5"><i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i><h4>Erro ao carregar leads</h4></div>');
                    }
                }
            });
        }

        function carregarMaisLeads() {
            offset += limit;
            filter(true);
        }

        function finalizarPedido() {
            if (LEADS_SELECIONADAS.length !== LIMITE_DE_LEADS_SELECIONADAS) {
                alertify.error(`Selecione exatamente ${LIMITE_DE_LEADS_SELECIONADAS} leads para continuar`);
                return;
            }

            $("#btnFinalizarPedido").prop("disabled", true).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Processando...');

            $.ajax({
                method: "POST",
                url: '{{url("pacotes/comprar")}}',
                data: {
                    ids_leads: LEADS_SELECIONADAS.map(lead => lead.id),
                    id_pacote: ID_PACOTE,
                    "_token": "{{csrf_token()}}"
                },
                success: (res) => {
                    $("#btnFinalizarPedido").prop("disabled", false).html('<i class="fas fa-check-circle mr-2"></i>Finalizar Compra');

                    if (res.status) {
                        alertify.success(res.msg);
                        MODAL_LISTAGEM_LEADS.modal("hide");
                    } else {
                        alertify.error(res.msg);
                    }
                },
                error: () => {
                    $("#btnFinalizarPedido").prop("disabled", false).html('<i class="fas fa-check-circle mr-2"></i>Finalizar Compra');
                    alertify.error("Erro ao processar pedido. Tente novamente.");
                }
            });
        }

        $(document).ready(function () {
            $('.select2-multiple').select2({
                width: '100%',
                placeholder: 'Selecione uma ou mais opções',
                allowClear: true
            });

            MODAL_LISTAGEM_LEADS.on('hidden.bs.modal', function () {
                $("#card_container").html("");
                offset = 0;
            });
        });
    </script>
@endsection
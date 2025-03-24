@extends("templates.list")
@section("title")
    <!-- Page Heading -->
    <!-- <div class="d-flex justify-content-between mb-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url("/")}}">Área do Cliente</a></li>
              <li class="breadcrumb-item active" aria-current="page">Comprar Pacote de Leads</li>
            </ol>
        </nav>
    </div> -->
@endsection
@section("tabela")

<div class="container d-flex justify-content-center">

    <div class="row w-100 justify-content-center">


        @foreach($pacotes as $pacote)
            <div class="col-12 col-lg-4">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <h2 class="card-title text-center font-weight-bold">{{$pacote->name}}</h2>
                        <p class="card-text">{!! $pacote->descricao !!}</p>
                        <button href="#" class="btn btn-primary w-100" onclick="abrirModal({{$pacote->qtd_leads}}, {{$pacote->id}}, {{$pacote->qtd_desconto}})">COMPRAR</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


      

</div>

{{-- MODAL LEADS --}}

<div class="modal" tabindex="-1" id="listagemLeads">
  <div class="modal-dialog modal-xl" style="
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
    ">
    <div class="modal-content"
    style="
  height: auto;
  min-height: 100%;
  border-radius: 0;
  width: 100vw !important;
  color: black;
"
    >
      <div class="modal-header">
        <h5 class="modal-title">Escolha <span id="quantidadeLeadsAescolher">0</span> LEADS</h5>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Escolha o DDD</label>
                    <select onchange="filter()" class="form-control select2-multiple" id="ddd_choice" multiple="multiple">
                        @for($i = 1; $i < 100; $i++)
                            <option value="{{$i}}">DDD ({{$i}})</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Escolha o tipo de lead</label>
                    <select onchange="filter()" class="form-control select2-multiple" id="tipo_choice" multiple="multiple">
                        @foreach($tipos as $tipo)
                            <option value="{{$tipo->id}}">{{$tipo->nome}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Escolha o plano de lead</label>
                    <select onchange="filter()" class="form-control select2-multiple" id="plano_choice" multiple="multiple">
                        @foreach($planos as $plano)
                            <option value="{{$plano->id}}">{{$plano->nome}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div style="height: 65vh;overflow: hidden;overflow-y: auto;">
            
            <div class="row justify-content-center" id="lead_main">
                <div class="container mt-4" id="lead_main">
                    <div class="row" id="card_container">
                        <!-- Os cards serão inseridos dinamicamente aqui -->
                    </div>
                    <div class="row w-100 justify-content-center mt-3">
                        <button class="btn btn-primary" onclick="carregarMaisLeads()">Carregar mais leads</button>
                    </div>
                </div>                
            </div>


            <div class="d-flex justify-content-center">
                <strong id="loading_spinner_lead">Carregando...</strong>
            </div>


        </div>
      </div>
      <div class="modal-footer">
        <div class="mr-auto">
            <h6>Valor das leads:<span id="totalLeadsSelecionadas">0,00</span></h6>
            <h6>Você vai pagar: <span id="totalLeadsSelecionadasDesconto">0,00</span></h6>
            <h6>Você economizou: <span id="totalEconomia"></span></h6>
        </div>
        <button type="button" class="btn btn-secondary" onclick="fecharModal()">Fechar</button>
        <button type="button" class="btn btn-primary" onclick="finalizarPedido()" id="btnFinalizarPedido" disabled>Finalizar Pedido</button>
      </div>
    </div>
  </div>
</div>


@endsection
@section("script")
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css"/>
<!-- Default theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/default.min.css"/>
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/semantic.min.css"/>
<!-- Bootstrap theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/bootstrap.min.css"/>

<!-- JS Select2 -->

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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
        TOTAL_DESCONTO = desconto
        LEADS_SELECIONADAS = [];
        $("#quantidadeLeadsAescolher").html(LIMITE_DE_LEADS_SELECIONADAS);
        filter();
        MODAL_LISTAGEM_LEADS.modal("show");


        // CONFIGURAR O SCROLL

    }

    function fecharModal() {
        MODAL_LISTAGEM_LEADS.modal("hide");
    }

    function selecionarLead(id_lead, preco) {
        let converterPreco = String(preco).replaceAll(".", "").replaceAll(",", ".");
        let novoPreco = Number(converterPreco);
        // verificar se é possível selecionar mais uma lead
        //verificar se a lead já foi selecionada:

        if(LEADS_SELECIONADAS.find(lead => lead.id == id_lead)) {
            // REMOVER A LEAD
            LEADS_SELECIONADAS = LEADS_SELECIONADAS.filter(lead => lead.id != id_lead);
        } else {
            LEADS_SELECIONADAS.push({
                id: id_lead,
                preco: novoPreco
            });
        }
        desabilitarButtons();
        gerarTotal();
    }

    function desabilitarButtons() {
        if(LEADS_SELECIONADAS.length == LIMITE_DE_LEADS_SELECIONADAS) {
            $('input[type="checkbox"]:not(:checked)').prop('disabled', true);
            $("#btnFinalizarPedido").prop("disabled", false);
        } else {
            $("#btnFinalizarPedido").prop("disabled", true);
            $('input[type="checkbox"]:not(:checked)').prop('disabled', false);
        }

    }

    function gerarTotal() {
        $("#quantidadeLeadsAescolher").html(LIMITE_DE_LEADS_SELECIONADAS - LEADS_SELECIONADAS.length);
        if(LEADS_SELECIONADAS.length == 0) {
            $("#totalLeadsSelecionadas").html("R$ 0,00");
            $("#totalLeadsSelecionadasDesconto").html("R$ 0,00");
            return;
        }
        let total = LEADS_SELECIONADAS.reduce((a, b) => {
            let total = a.preco + b.preco;
            return {
                preco: total
            }
        });
        $("#totalLeadsSelecionadas").html(total.preco.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
        if(total.preco > 0) {
            // DESCONTO DE 5 % EXEMPLO
            let DESCONTO = TOTAL_DESCONTO;
            let totalDesconto = ( (total.preco * DESCONTO) / 100 );
            $("#totalEconomia").html(totalDesconto.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
            $("#totalLeadsSelecionadasDesconto").html((total.preco - ( (total.preco * DESCONTO) / 100 )).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
        }
        
    }

    function filter(paginacao = false) {
        gerarTotal();
        $("#lead_main").css("display", "none");
        $("#loading_spinner_lead").css("display", "");
        let ddd = $("#ddd_choice").val();
        let tipo = $("#tipo_choice").val();
        let plano = $("#plano_choice").val();
        $.ajax({
            method: "GET",
            url: "{{url("leads/search")}}",
            data: {
                "selecionavel": "true",
                ddd,
                tipo,
                plano,
                offset: paginacao ? offset : 0,
                limit: limit,
            },
            success: function(res) {
                $("#loading_spinner_lead").css("display", "none");
                $("#lead_main").css("display", "");
                if(res?.leads) {
    let cardContainer = $("#card_container");
    if(!paginacao) {
        cardContainer.html(""); // Limpa o container de cards se não estiver paginando
    }
    let html = cardContainer.html();
    res.leads.map(lead => {
        html += `
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <!-- Checkbox para selecionar o lead -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkbox_${lead.id}" onclick="selecionarLead('${lead.id}', '${lead.preco}')">
                            <label class="form-check-label" for="checkbox_${lead.id}">
                                Selecionar Lead
                            </label>
                        </div>

                        <!-- Informações do lead -->
                        <h5 class="card-title">Lead #${lead.id}</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Tipo:</strong> ${lead?.tipo?.nome || ''}</li>
                            <li class="list-group-item"><strong>Preço:</strong> R$ ${lead?.preco || '0.00'}</li>
                            <li class="list-group-item"><strong>CNPJ:</strong> ${lead?.cnpj ? "SIM" : "NÃO"}</li>
                            <li class="list-group-item"><strong>Plano:</strong> ${lead?.plano?.nome || ''}</li>
                            <li class="list-group-item"><strong>Deseja cotar:</strong> ${lead?.plano?.nome || ''}</li>
                            <li class="list-group-item"><strong>Região (DDD):</strong> ${lead?.ddd || ''}</li>
                            <li class="list-group-item"><strong>Idade:</strong> ${lead?.idade || ''}</li>
                            <li class="list-group-item"><strong>Horário Partida:</strong> ${lead?.horario_partida || ''}</li>
                        </ul>
                    </div>
                </div>
            </div>
        `;
    });
    cardContainer.html(html); // Atualiza o container com os novos cards

    // Marcar os checkboxes
    desabilitarButtons();
    LEADS_SELECIONADAS.map(lead => {
        let checkbox = $(`#checkbox_${lead.id}`);
        checkbox.prop("checked", true);
        checkbox.prop("disabled", false);
    });

    gerarTotal();
}

            }
        })
    }

    function finalizarPedido() {
        let baseUrl = '{{url("pacotes/comprar")}}';
        $("#lead_main").css("display", "none");
        $("#loading_spinner_lead").css("display", "");
        $.ajax({
            method: "POST",
            url: baseUrl,
            data: {
                ids_leads: LEADS_SELECIONADAS.map(lead => lead.id),
                id_pacote: ID_PACOTE,
                "_token": "{{csrf_token()}}"
            },
            success: (res) => {
                filter();
                if(res.status) {
                    alertify.success(res.msg);
                    MODAL_LISTAGEM_LEADS.modal("hide");
                } else {
                    alertify.error(res.msg);
                }
            }

        })
    }

    function carrerarMaisLeads() {
        offset += limit;
        filter(true);
    }

    $(document).ready(function() {
        $('.select2-multiple').select2({
            width: '100%', // Faz com que o select use toda a largura
            placeholder: 'Selecione uma ou mais opções', // Placeholder para as seleções múltiplas
            allowClear: true // Adiciona o botão de limpar
    })});


    // setInterval(() => {
    //     filter();
    // }, 1000);
</script>
@endsection
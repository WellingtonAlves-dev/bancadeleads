@extends("templates.list")
@section("title")
    <!-- Page Heading -->
    <div class="d-flex justify-content-between mb-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Área do Cliente</a></li>
              <li class="breadcrumb-item active" aria-current="page">Comprar Pacote de Leads</li>
            </ol>
        </nav>
    </div>
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
                    <select onchange="filter()" class="form-control" id="ddd_choice">
                        <option value="todos">Todos</option>
                        @for($i = 1; $i < 100; $i++)
                            <option value="{{$i}}">DDD ({{$i}})</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Escolha o tipo de lead</label>
                    <select onchange="filter()" class="form-control" id="tipo_choice">
                        <option value="todos">Todas</option>
                        @foreach($tipos as $tipo)
                            <option value="{{$tipo->id}}">{{$tipo->nome}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Escolha o plano de lead</label>
                    <select onchange="filter()" class="form-control" id="plano_choice">
                        <option value="todos">Todas</option>
                        @foreach($planos as $plano)
                            <option value="{{$plano->id}}">{{$plano->nome}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div style="height: 65vh;overflow: hidden;overflow-y: auto;">
            
            <div class="row justify-content-center" id="lead_main">
                <table class="table table-bordered" style="font-size: 11px">
                    <thead>
                        <th>#</th>
                        <th>TIPO</th>
                        <th>PREÇO</th>
                        <th>CNPJ</th>
                        <th>PLANO</th>
                        <th>REGIÃO</th>
                        <th>PERFIL</th>
                        <th>Adicionado há</th>
                    </thead>
                    <tbody id="tbody_main">

                    </tbody>
                </table>
                <div class="row w-100 justify-content-center">
                        <button class="btn btn-primary" onclick="carrerarMaisLeads()">Carregar mais leads</button>
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
                    let tabela = $("#tbody_main");
                    if(!paginacao) {
                        tabela.html("");
                    }
                    let html = tabela.html();
                    res.leads.map(lead => {
                        html += `
                            <tr>
                                <td>
                                    <input type='checkbox' id="checkbox_${lead.id}" onclick="selecionarLead('${lead.id}', '${lead.preco}')"/>    
                                </td>
                                <td>${lead?.tipo?.nome}</td>
                                <td>R$ ${lead?.preco}</td>
                                <td>${lead?.cnpj ? "SIM" : "NÃO"}</td>
                                <td>${lead?.plano?.nome}</td>
                                <td>${lead?.ddd}</td>
                                <td>${lead?.idade}</td>
                                <td>${lead?.horario_partida}</td>
                            </tr>
                        `;
                    });
                    tabela.html(html);
                    // marcar
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

    // setInterval(() => {
    //     filter();
    // }, 1000);
</script>
@endsection
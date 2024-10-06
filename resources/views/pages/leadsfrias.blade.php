@extends("templates.default")
@section("content")

@php

$estados = array(
    "11" => "(11) São Paulo",
    "12" => "(12) São Paulo",
    "13" => "(13) São Paulo",
    "14" => "(14) São Paulo",
    "15" => "(15) São Paulo",
    "16" => "(16) São Paulo",
    "17" => "(17) São Paulo",
    "18" => "(18) São Paulo",
    "19" => "(19) São Paulo",
    "21" => "(21) Rio de Janeiro",
    "22" => "(22) Rio de Janeiro",
    "24" => "(24) Rio de Janeiro",
    "27" => "(27) Espírito Santo",
    "28" => "(28) Espírito Santo",
    "31" => "(31) Minas Gerais",
    "32" => "(32) Minas Gerais",
    "33" => "(33) Minas Gerais",
    "34" => "(34) Minas Gerais",
    "35" => "(35) Minas Gerais",
    "37" => "(37) Minas Gerais",
    "38" => "(38) Minas Gerais",
    "41" => "(41) Paraná",
    "42" => "(42) Paraná",
    "43" => "(43) Paraná",
    "44" => "(44) Paraná",
    "45" => "(45) Paraná",
    "46" => "(46) Paraná",
    "47" => "(47) Santa Catarina",
    "48" => "(48) Santa Catarina",
    "49" => "(49) Santa Catarina",
    "51" => "(51) Rio Grande do Sul",
    "53" => "(53) Rio Grande do Sul",
    "54" => "(54) Rio Grande do Sul",
    "55" => "(55) Rio Grande do Sul",
    "61" => "(61) Distrito Federal",
    "62" => "(62) Goiás",
    "63" => "(63) Tocantins",
    "64" => "(64) Goiás",
    "65" => "(65) Mato Grosso",
    "66" => "(66) Mato Grosso",
    "67" => "(67) Mato Grosso do Sul",
    "68" => "(68) Acre",
    "69" => "(69) Rondônia",
    "71" => "(71) Bahia",
    "73" => "(73) Bahia",
    "74" => "(74) Bahia",
    "75" => "(75) Bahia",
    "77" => "(77) Bahia",
    "79" => "(79) Sergipe",
    "81" => "(81) Pernambuco",
    "82" => "(82) Alagoas",
    "83" => "(83) Paraíba",
    "84" => "(84) Rio Grande do Norte",
    "85" => "(85) Ceará",
    "86" => "(86) Piauí",
    "87" => "(87) Pernambuco",
    "88" => "(88) Ceará",
    "89" => "(89) Piauí",
    "91" => "(91) Pará",
    "92" => "(92) Amazonas",
    "93" => "(93) Pará",
    "94" => "(94) Pará",
    "95" => "(95) Roraima",
    "96" => "(96) Amapá",
    "97" => "(97) Amazonas",
    "98" => "(98) Maranhão",
    "99" => "(99) Maranhão"
);


@endphp
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 font-weight-bold">Leads Frias</h1>
                    </div>
                   
                   
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="card shadow-md">
                                <div class="card-header">
                                    Filtro
                                </div>
                                <div class="card-body row">
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>Escolha o DDD</label>
                                            <select class="form-control" id="ddd_choice">
                                                <option value="todos">Todos</option>
                                                @foreach($estados as $key => $estado)
                                                    <option value="{{$key}}">{{$estado}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label>Escolha o tipo de lead</label>
                                            <select class="form-control" id="tipo_choice">
                                                <option value="todos">Todas</option>
                                                @foreach($tipos as $tipo)
                                                    <option value="{{$tipo->id}}">{{$tipo->nome}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label>Escolha o plano</label>
                                            <select class="form-control" id="plano_choice">
                                                <option value="todos">Todas</option>
                                                @foreach($planos as $plano)
                                                    <option value="{{$plano->id}}">{{$plano->nome}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="form-group">
                                            <label>Filtrar</label>
                                            <button onclick="filter()" class="btn btn-primary w-100">
                                                <i class="fas fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

@if(Session::has("success_save"))
<div class="alert alert-warning alert-dismissible fade show mt-5" role="alert">
  {{Session::get("success_save")}}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
                    <div class="row justify-content-center" id="lead_main">
                    </div>


                    <div class="d-flex justify-content-center">
                        <strong id="loading_spinner_lead">Carregando...</strong>
                    </div>
@endsection

@section("script")
<script>
    let page = 0;
    let totalDePage = 0;
    let totaldeLeads = 0;
    let limit = 20;
    const totalLeadPorPagina = 20;
    function comprar(id_lead) {
        $("#modalComprarLead").modal("hide");
        MODAL_LOADING.modal("show");
        $.ajax({
            method: "POST", 
            url: "{{url("/leads/frias/comprar")}}" + "/" + id_lead,
            data: {
                "_token": "{{csrf_token()}}"
            },
            success: (res) => {
                setSaldoInfo();
                MODAL_LOADING.modal("hide");
                DIV_GENERIC.html(res);
                $("#modal_alert").modal("show");
            },
            error: (err, xhr) => {
                MODAL_LOADING.modal("hide");
                DIV_GENERIC.html(err.responseText);
                $("#modal_alert").modal("show");
            }
        })

    }

    function atenderLead(id_lead) {
        MODAL_LOADING.modal("show");
        $.ajax({
            method: "GET", 
            url: "{{url("/leads/frias/comprar")}}" + "/" + id_lead,
            success: (res) => {
                MODAL_LOADING.modal("hide");
                DIV_GENERIC.html(res);
                $("#modalComprarLead").modal("show");
                filter();
            },
            error: (err, xhr) => {
                MODAL_LOADING.modal("hide");
                DIV_GENERIC.html(err.responseText);
                $("#modal_alert").modal("show");
            }
        })
    }

    function filter() {
        let ddd = $("#ddd_choice").val();
        let tipo = $("#tipo_choice").val();
        let plano = $("#plano_choice").val();

        $.ajax({
            method: "GET",
            url: "{{url("leads/frias/search")}}",
            data: {
                ddd,
                tipo,
                plano,
                offset: page,
                limit: limit
            },
            success: function(res) {
                $("#loading_spinner_lead").css("display", "none");
                $("#lead_main").html(res);
                totaldeLeads = $("#totalLeads").val();
            }
        })
    }
    filter();
    $(window).scroll(function() {
        carregarLeads();
    });

    $('body').bind('touchmove', function(e) { 
        carregarLeads();
    });

    function carregarLeads() {
        if($(window).scrollTop() + $(window).height() >= $(document).height() - 400) {
            if(Number($("#totalLeadsCarregada").val()) < Number(totaldeLeads)) {
                limit += totalLeadPorPagina;
                $("#loading_spinner_lead").css("display", "");
                filter();
            }
        }
    }

    setInterval(() => {
        filter();
    }, 1000);
</script>
@endsection
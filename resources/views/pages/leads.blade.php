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
<style>
    body {
        background-color: white;
    }

    .filter-btn {
        border: 2px solid #943537;
        color: #943537;
        background-color: transparent;
        transition: all 0.3s;
    }

    .filter-btn:hover {
        background-color: #943537;
        color: white;
    }

    .modal-filter {
        padding: 20px;
        border-radius: 8px;
        background-color: white;
    }

    .modal-header, .modal-footer {
        border: none;
    }

    .lead-details {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
        font-size: 1rem;
    }

    @media (min-width: 768px) {
        .lead-logo, .lead-details {
            flex-direction: row;
            align-items: center;
        }

        .lead-logo {
            max-width: 150px;
            margin-right: 15px;
        }
    }

    /* Estilo básico do cartão */
.lead-card-1 {
    display: flex;                        /* 1 */
    flex-wrap: wrap;                     /* 2 */
    background-color: white !important;  /* 3 */
    padding-right: 15px;                       /* 4 */
    margin-bottom: 10px;                        /* 5 */
    margin-top: 10px;
    border-radius: 10px;                 /* 6 */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* 7 */
    height: 250px;
}

/* Seção do Logo */
.lead-logo-2 {
    width: 200px;
    height: 100%;                        /* 9 */
    border-radius: 10px 0 0 10px;       /* 10 */
    overflow: hidden;                    /* 11 */
    display: flex;                       /* 12 */
    align-items: center;                 /* 13 */
    justify-content: center;             /* 14 */
}

.logo-image-3 {
    width: 100%;                        /* 15 */
    height: 100%;                       /* 16 */
    object-fit: cover;                  /* 18 */
}

/* Seção de Detalhes e CTA */
.lead-details-4 {
    flex: 1;                            /* 19 */
    display: flex;                      /* 20 */
    flex-direction: column;             /* 21 */
    padding: 15px;                /* 22 */
    position: relative;                 /* 23 */
    background-color: white;           /* 24 */
    border-radius: 0 10px 10px 0;      /* 25 */
}

/* Horário no canto superior direito */
.lead-time-5 {
    position: absolute;                 /* 26 */
    top: 10px;                          /* 27 */
    right: 10px;                        /* 28 */
    color: #4A5178;                     /* 29 */
    font-size: 12px;                   /* 30 */
}

.lead-time-5 .fas {
    color:#943537;
}

/* Informações da Lead */
.lead-info-6 {
    flex: 1;                            /* 31 */
    display: flex;                      /* 32 */
    flex-direction: column;             /* 33 */
    justify-content: center;            /* 34 */
}
.lead-info-6 .lead-info-principal {
    display: flex;
    flex-direction: column;
}
.lead-info-6 .lead-info-secundaria {
    gap: 12px;
}

.lead-plan-7 {
    font-weight: bold;                  /* 36 */
    font-size: 20px;                    /* 37 */
    color: rgb(74,81,120);              /* 38 */
}

.lead-price-8 {
    font-weight: bold;                  /* 39 */
    font-size: 24px;                    /* 40 */
}
.lead-price-8 {
    font-size: 34px;
}

.lead-age-9, .lead-location-10, .lead-preference-11, .lead-cnpj-12 {
    font-size: 16px;                    /* 42 */
    color: #4A5178;                     /* 43 */
    margin-top: 5px;                   /* 44 */
}

/* Seção do Botão */
.lead-button-13 {
    text-align: right;                  /* 46 */
}

.cta-button-14 {
    background-color: transparent;      /* Fundo transparente */
    color: #28a745;                     /* Cor do texto verde */
    border: 2px solid #28a745;          /* Borda verde */
    border-radius: 5px;                 /* Mantém a borda arredondada */
    padding: 8px 15px;                  /* Mesma configuração de padding */
    cursor: pointer;                    /* Mantém o cursor pointer */
    font-weight: bold;                  /* Mantém o texto em negrito */
    transition: background-color 0.3s ease; /* Transição suave no hover */
}

.cta-button-14:hover {
    background-color: #28a745;          /* Fundo verde no hover */
    color: white;                       /* Texto branco no hover */
}
.grupo-infos-btn {
    display: flex; 
    align-items: center; 
    justify-content: space-between;
}
@media (max-width: 768px) {
    .lead-card-1 {
        flex-direction: column;          /* Colocar os itens em coluna */
        height: auto;                    /* Altura automática */
    }
    
    .lead-logo-2 {
        width: 100%;                     /* Logo ocupa 100% da largura */
        height: 150px;                   /* Ajusta a altura do logo */
        border-radius: 10px 10px 0 0;    /* Arredonda o topo */
    }

    .lead-details-4 {
        border-radius: 0 0 10px 10px;    /* Arredonda a parte inferior */
    }

    .lead-price-8 {
        font-size: 28px;                 /* Diminui o tamanho do preço */
    }
    .grupo-infos-btn {
        justify-content: center;
        flex-direction: column;
        margin-top: 12px;
    }

    .lead-time-5 {
        font-size: 16px;                 /* Reduz o tamanho da fonte do horário */
        display: flex;
        justify-content: center;
        position: relative;
        text-align: center;
        margin-bottom: 5px !important;
        margin-top: 0px;
        top: 0;
        left: 0;
        right: 0;
    }
    .lead-info-6 {
        margin-bottom: 15px;
    }
}

/* Ajustes para dispositivos móveis */
@media (max-width: 480px) {
    .lead-card-1 {
        padding-right: 10px;             /* Reduz o padding lateral */
    }
    
    .lead-price-8 {
        font-size: 24px;                 /* Ajusta o tamanho do preço para caber */
    }

    .lead-plan-7 {
        font-size: 18px;                 /* Ajusta o tamanho da fonte do plano */
    }
    
    .cta-button-14 {
        padding: 6px 10px;               /* Diminui o padding do botão */
        font-size: 14px;                 /* Diminui o tamanho do texto */
    }
}

</style>

<!-- Page Heading -->
<div class="d-flex justify-content-between mb-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{url("/")}}">Àrea do Cliente</a></li>
          <li class="breadcrumb-item active" aria-current="page">Comprar Lead Avulsa</li>
        </ol>
    </nav>
</div>
@if(Session::has("success_save"))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    {{ Session::get("success_save") }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if($avisos ?? false)
<div class="alert alert-info mb-4">
    {!! $avisos->aviso ?? "" !!}
</div>
@endif

<div class="container">
    <div class="d-flex justify-content-between">
        <button class="btn filter-btn" data-toggle="modal" data-target="#filterModal">Filtrar Leads</button>
        <button class="cta-button-14" onclick="window.location.href = '{{url("/checkout")}}'">Adicionar Crédito</button>
    </div>
</div>

<!-- Main Content Area -->
<div class="container lead_main_page" id="lead_main">
    <!-- Leads serão carregados via AJAX aqui -->
</div>

<!-- Loading Spinner -->
<div class="text-center my-4">
    <strong id="loading_spinner_lead" style="display: none;">Carregando...</strong>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-filter">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filtrar Leads</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="ddd_choice">Selecione o(s) DDD(s)</label>
                        <select class="form-control select2-multiple" id="ddd_choice" multiple="multiple">
                            @foreach($estados as $key => $estado)
                                <option value="{{$key}}">{{$estado}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipo_choice">Selecione o(s) tipo(s) de lead</label>
                        <select class="form-control select2-multiple" id="tipo_choice" multiple="multiple">
                            @foreach($tipos as $tipo)
                                <option value="{{$tipo->id}}">{{$tipo->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="plano_choice">Selecione o(s) plano(s)</label>
                        <select class="form-control select2-multiple" id="plano_choice" multiple="multiple">
                            @foreach($planos as $plano)
                                <option value="{{$plano->id}}">{{$plano->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="clearFilters()">Limpar Filtros</button>
                <button type="button" class="btn filter-btn w-100" onclick="filter(true)">Aplicar Filtro</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- JS Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    let page = 0;
    let limit = 20;
    const totalLeadPorPagina = 20;
    function comprar(id_lead) {
        $("#modalComprarLead").modal("hide");
        MODAL_LOADING.modal("show");
        $.ajax({
            method: "POST", 
            url: "{{url("/leads/comprar")}}" + "/" + id_lead,
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


    function filter(fechar = false) {
        let ddd = $("#ddd_choice").val()
        let tipo = $("#tipo_choice").val()
        let plano = $("#plano_choice").val()

        $.ajax({
            method: "GET",
            url: "{{ url('leads/search') }}",
            data: { ddd, tipo, plano, offset: page, limit: limit },
            success: function(res) {
                $("#loading_spinner_lead").css("display", "none");
                $("#lead_main").html(res);
                totaldeLeads = $("#totalLeads").val();
                if(fechar) {
                    $('#filterModal').modal('hide'); // Fecha o modal após a aplicação dos filtros
                }
            }
        });
    }

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 400) {
            if (Number($("#totalLeadsCarregada").val()) < Number(totaldeLeads)) {
                limit += totalLeadPorPagina;
                filter();
            }
        }
    });

    function atenderLead(id_lead) {
        MODAL_LOADING.modal("show");
        $.ajax({
            method: "GET", 
            url: "{{url("/leads/comprar")}}" + "/" + id_lead,
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


$(document).ready(function() {
    $('.select2-multiple').select2({
        width: '100%', // Faz com que o select use toda a largura
        placeholder: 'Selecione uma ou mais opções', // Placeholder para as seleções múltiplas
        allowClear: true // Adiciona o botão de limpar
    });
});

// Função para limpar os filtros
function clearFilters() {
    $('.select2-multiple').val(null).trigger('change'); // Limpa as seleções
}

    setInterval(() => {
        filter();
    }, 1000);

    filter();
</script>
@endsection

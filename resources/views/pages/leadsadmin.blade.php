@extends("templates.default")
@section("content")
<link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0  font-weight-bold">Leads</h1>
                    </div>

                    <div class="row justify-content-end">
                      
                      <div class="btn-group" role="group" aria-label="Exemplo básico">
                        <button onclick="window.location.href = '{{url("admin/leads/novo")}}'" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i>
                            Nova Lead
                        </button>
                        <button onclick="modalImportar(false)" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i>
                            Importar leads
                        </button>
                        <button onclick="modalImportar(true)" class="btn btn-warning btn-sm">
                            <i class="fas fa-file-excel"></i>
                            Importar leads <small>frías</small>
                        </button>
                        <button onclick="$('#modal_exportar_leads').modal('show')" class="btn btn-secondary btn-sm">
                            <i class="fas fa-download"></i>
                            Exportar leads
                        </button>
                    </div>  

                    </div>
                    <div class="row mt-4 justify-content-end">
                      <form method="GET" class="col-12 col-sm-3 float-right">
                        <div class="input-group">
                            <input value="{{request("search")}}" placeholder="Pesquisar ID|E-mail" type="text" class="form-control" name="search" id="search"/>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    </div>
                    <div class="row  p-2 mt-1 justify-content-center">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                              <a class="nav-link {{!request()->query('tab') ? "active" : ""}}" href="{{url("/leads")}}">Todas</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link {{request()->query('tab') == "nao_vendidas" ? "active" : ""}}" href="{{url("/leads?tab=nao_vendidas")}}">Ativas</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link {{request()->query('tab') == 'vendidas' ? "active" : ""}}" href="{{url("/leads?tab=vendidas")}}">Vendidas</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link {{request()->query('tab') == 'arquivadas' ? "active" : ""}}" href="{{url("/leads?tab=arquivadas")}}" tabindex="-1" aria-disabled="true">Arquivadas</a>
                            </li>
                          </ul>
                        <table class="table table-responsive" style="font-size: 12px">
                            <thead>
                                <th>ID</th>
                                <th>ATIVO</th>
                                <th>TIPO</th>
                                <th>PLANO</th>
                                <th>PREÇO</th>
                                <th>HORARIO PARTIDA</th>
                                <th>DIAS DISPONIVEIS</th>
                                <th>PERFIL</th>
                                <th>DDD</th>
                                <th>TELEFONE</th>
                                <th>E-MAIL</th>
                                <th>CLIENTE</th>
                                <th>COMPRADOR</th>
                                <th>RESETAR TIME/EDITAR</th>
                            </thead>
                            <tbody id="lead_main">
                                @foreach($leads as $lead)

                                <tr style="background-color: gainsboro; color: black;">
                                    <td>{{$lead->id}}</td>
                                    <td>
                                        <input type="checkbox" onclick="toggleStatusLead({{$lead->id}}, this)" @checked($lead->ativo)/>
                                    </td>
                                    <td>{{$lead->tipo->nome}}</td>
                                    <td>{{$lead->plano->nome}}</td>
                                    <td>{{$lead->preco}}</td>
                                    <td>{{$lead->horario_partida}}</td>
                                    <td>{{$lead->dias_disponivel}}</td>
                                    <td>{{$lead->idade}}</td>
                                    <td>{{$lead->ddd}}</td>
                                    <td>{{$lead->telefone}}</td>
                                    <td>{{$lead->email}}</td>
                                    <td>{{$lead->nome_lead}}</td>
                                    <td>
                                      @if($lead->id_user)
                                        <a href="{{url("/admin/users/editar/".$lead->id_user)}}">{{$lead->name}}</a>
                                      @else 
                                        N/A
                                      @endif
                                    </td>
                                    <td>
                                        <a href="{{url("admin/leads/reset/".$lead->id)}}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-clock"></i>
                                        </a>
                                        <a href="{{url("admin/leads/editar/".$lead->id)}}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row justify-content-end mt-3">
                        {{$paginator->onEachSide(0)->withQueryString()->links()}} 
                    </div>

@if(Session::has("success_save"))
<div class="alert alert-warning alert-dismissible fade show mt-5" role="alert">
  {{Session::get("success_save")}}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif




<div class="modal" tabindex="-1" role="dialog" id="modal_importar_leads">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="title_importar_leads">Importar leads</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="POST" enctype="multipart/form-data" action="{{url("admin/leads/importar")}}" id="form_importar_lead">
                @csrf
                <div class="form-group">
                        <label>Arquivo com as leads</label>
                        <input type="file" name="file"/>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-success" onclick="$('#form_importar_lead').submit()">Enviar</button>
        </div>
      </div>
    </div>
  </div>


  <div style="color: black" class="modal" tabindex="-1" role="dialog" id="modal_exportar_leads">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Exportar leads</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">


          <div class="form-group">
            <label>DDD</label>
            <select class="form-control" id="ddd_filter">
              <option value="">Todos</option>
              @for($i = 11; $i < 64; $i++)
                <option value="{{$i}}">{{$i}}</option>
              @endfor
            </select>
          </div>

          <div class="form-group">
            <label>Período inicial</label>
            <input type="date" class="form-control" id="data_inicial"/>
            <span class="text-danger" id="periodo_inicial_alerta"></span>
          </div>

          <div class="form-group">
            <label>Período final</label>
            <input type="date" class="form-control" id="data_final"/>
            <span class="text-danger" id="periodo_final_alerta"></span>
          </div>
          <small>Para fazer o download em todas as datas, basta deixar os campos de período em branco.</small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-success" onclick="exportarLeads()">Exportar</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@section("script")
<script>
    function toggleStatusLead(id_lead, element) {
        let status = element.checked ? "ativo" : "inativo";
        $.ajax({
            method: "GET",
            url: "{{url("/admin/lead")}}" + "/" + id_lead + "/" + status,
            success: function(res) {
                console.log("lead atualizada com sucesso")
            },
            error: err => {
                alert("Não foi possível atualizar a lead. entre em contato com a WellTech");
            }
        })
    }

    function modalImportar(lead_fria = false) {
      $('#modal_importar_leads').modal('show')
      const URL_BASE = "{{url("/")}}";
      let URL = URL_BASE + "/";
      if(lead_fria) {
        URL += "admin/leads/frias/importar";
        $("#title_importar_leads").html("Importar leads <small>frias</small>");
        $("#form_importar_lead").prop("action", URL)
      } else {
        URL += "admin/leads/importar";
        $("#title_importar_leads").html("Importar leads");
        $("#form_importar_lead").prop("action", URL)
      }
    }

    function exportarLeads() {
      let data_inicial = $("#data_inicial");
      let data_final = $("#data_final");
      let alerta_inicial = $("#periodo_inicial_alerta");
      let alerta_final = $("#periodo_final_alerta");
      let erros = 0;
      let validar = false;

      if(data_inicial.val().length > 0 || data_final.val().length > 0) {
        validar = true;
      } else {
        alerta_inicial.html("");
        alerta_final.html("");
      }


      if(validar) {
        if(data_inicial.val().length === 0) {
          alerta_inicial.html("O campo período inicial é obrigatório");
          erros++;
        } else {
          alerta_inicial.html("");
        }

        if(data_final.val().length === 0) {
          alerta_final.html("O campo período inicial é obrigatório");
          erros++;
        } else {
          alerta_final.html("");
        }
      }


      if(validar && erros > 0) {
        return;
      }

      const params = new URLSearchParams({
          data_inicial: data_inicial.val(),
          data_final: data_final.val(),
          ddd: $("#ddd_filter").val()
      });
      window.location.href = "{{url("admin/leads/exportar")}}" + "?" + params;
    }

</script>
@endsection
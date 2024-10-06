@extends("templates.default")
@section("content")
<link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0  font-weight-bold">Reposições</h1>
                    </div>

                    <div class="row  p-2 mt-1 justify-content-center">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                              <a class="nav-link {{!request()->query('tab') ? "active" : ""}}" href="{{url("/admin/reposicoes")}}">Todas</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link {{request()->query('tab') == "aguardando" ? "active" : ""}}" href="{{url("/admin/reposicoes?tab=aguardando")}}">Aguardando</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link {{request()->query('tab') == 'aprovada' ? "active" : ""}}" href="{{url("/admin/reposicoes?tab=aprovada")}}">Aprovada</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link {{request()->query('tab') == 'rejeitada' ? "active" : ""}}" href="{{url("/admin/reposicoes?tab=rejeitada")}}">Rejeitada</a>
                            </li>
                          </ul>
                        <table class="table w-100" style="font-size: 12px">
                            <thead>
                                <th>#</th>
                                <th>SOLICITANTE</th>
                                <th>ID LEAD</th>
                                <th>STATUS</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach($reposicoes as $reposicao)
                                    <tr>
                                        <td>
                                            {{ $reposicao->id }}
                                        </td>
                                        <td>
                                            <a target="_blank" href="{{url("admin/users/editar/".$reposicao->solicitante)}}">{{ucfirst($reposicao->user_name)}}</a>
                                        </td>
                                        <td>
                                            {{ $reposicao->lead_id }}
                                        </td>
                                        <td>
                                            {{ $reposicao->status }}
                                        </td>
                                        <td>
                                            <button onclick="viewReposicao('{{ $reposicao->id }}', `{{ $reposicao->descricao }}`, '{{ $reposicao->lead_id }}', '{{ $reposicao->solicitante }}', '{{ $reposicao->user_name }}')" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row justify-content-end mt-3">
                        {{$reposicoes->onEachSide(0)->withQueryString()->links()}} 
                    </div>

@if(Session::has("success_save"))
<div class="alert alert-warning alert-dismissible fade show mt-5" role="alert">
  {{Session::get("success_save")}}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif




<div class="modal" tabindex="-1" role="dialog" id="modal_reposicao">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Contestação solicitada</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="color: black !important">
            <table class="table" style="font-size: 12px">
              <tr>
                <th>SOLICITANTE: </th>
                <td id="solicitante_name_info"></td>
              </tr>
              <tr>
                <th>LEAD: </th>
                <td id="lead_info"></td>
              </tr>
            </table>
            <hr/>
            <label>Motivo da solicitação: </label><br/>
            <p id="reposicao_modal_descricao">

            </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="rejeitar()">Rejeitar</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="aprovar()">Aprovar</button>
        </div>
      </div>
    </div>
  </div>


@endsection

@section("script")
<script>
    let reposicao_solicitada = null;
    let reposicao_modal = $("#modal_reposicao");
    let reposicao_modal_descricao = $("#reposicao_modal_descricao");
    let reposicao_modal_solicitante = $("#solicitante_name_info");
    let reposicao_modal_lead = $("#lead_info");

    const base_url = "{{url("/")}}";

    function viewReposicao(id_reposicao, descricao, id_lead, solicitante_id, solicitante) {
        reposicao_solicitada = id_reposicao;
        reposicao_modal_solicitante.html(`<a href='${base_url + "/admin/users/editar/" + solicitante_id}' target='_blank'>${solicitante}</a>`)
        reposicao_modal_lead.html(`<a href='${base_url + "/admin/leads/editar/" + id_lead}' target='_blank'>#${id_lead}</a>`)
        reposicao_modal_descricao.html(descricao);
        reposicao_modal.modal("show");
    }

    function rejeitar() {
      if(confirm("Deseja realmente rejeitar essa solicitação de contestação?")) {

        let motivo_reprovacao = window.prompt("Descreva o motivo para rejetar está contestação");

        $.ajax({
          method: "POST",
          url: base_url + "/admin/reposicoes/rejeitar",
          data: {
            id_reposicao: reposicao_solicitada,
            motivo_reprovacao: motivo_reprovacao,
            _token: "{{csrf_token()}}"
          },
          success: function(res) {
            alert(res.msg);
            window.location.href = window.location.href;
          }
        })
      }
    }

    function aprovar() {
      if(confirm("Deseja realmente aprovar essa solicitação de contestação?")) {

        $.ajax({
          method: "POST",
          url: base_url + "/admin/reposicoes/aprovar",
          data: {
            id_reposicao: reposicao_solicitada,
            _token: "{{csrf_token()}}"
          },
          success: function(res) {
            alert(res.msg);
            window.location.href = window.location.href;
          }
        })
      }
    }

</script>
@endsection
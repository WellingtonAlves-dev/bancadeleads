@extends("templates.form")
@section("title")
Marketing
@endsection
@section("form")
<style>
.ms-container {
    width: 100% !important;
}
</style>

<div style="display: none">
{{json_encode($tokens)}}
</div>
<div class="d-flex justify-content-center">
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        DISPOSITIVOS</div>
                    <div class="h5 mb-0 font-weight-bold  font-weight-bold"> {{ $dipositivos ?? 0 }} </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-user fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="form-group">
      <label for="title">Titulo*</label>
      <input type="text" class="form-control" placeholder="Ex. Promoção!!" id="title"/>
      <span class="text-danger small" id="alert_title"></span>
    </div>
    <div class="form-group">
      <label for="title">Mensagem*</label>
      <textarea id="msg" class="form-control"></textarea>
      <span class="text-danger small" id="alert_msg"></span>
      <small>A notificação irá aparecer para todos os dispositivos</small>
    </div>
    <div class="form-group">
      <button onclick="enviarNotificacoes()" class="btn btn-primary">Enviar</button>
    </div>
  </div>
</div>

@endsection
@section("script")
<script>    
  function enviarNotificacoes() {
    if(!confirm("Deseja realmente enviar as notificações???")) {
      alert("Envio de notificações cancelado");
      return;
    }

    let title = $("#title").val();
    let msg = $("#msg").val();

    if(title.length === 0 ) {
      $("#alert_title").html("O campo titulo é obrigatório");
      return;
    } else {
      $("#alert_title").html("");
    }

    if(msg.length === 0 ) {
      $("#alert_msg").html("O campo titulo é obrigatório<br/>");
      return;
    } else {
      $("#alert_msg").html("");
    }

    $.ajax({
      method: "POST",
      url: "{{url("admin/marketing/mobile")}}",
      data: {
        title,
        msg,
        "_token": "{{csrf_token()}}"
      },
      success: (res) => {
        if(res.erro) {
          console.log(res);
          alert("Não foi possível enviar as notificações");
        } else {
          alert("Notificações enviadas com sucesso");
        }
      }
    })

  }
</script>
@endsection
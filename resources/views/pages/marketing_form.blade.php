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
<form method="POST">
    @csrf
    <div class="form-group">
        <label>Assunto do e-mail</label>
        <input type="text" class="form-control" id="assunto_email" placeholder="Ex. Promoção"/>
        <small class="text-danger" id="alerta_assunto_email"></small>
    </div>
    <div class="form-group">
        <label>Template de e-mail</label>
        <div class="input-group">
            <select class="form-control" id="template_id" name="template_id">
                <option disabled selected value> -- ESCOLHA UM TEMPLATE -- </option>
                @foreach($templates as $template)
                    <option value="{{$template->id}}">{{$template->title}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label>Modelo de e-mail</label>
        <textarea name="template" id="template"></textarea>
        <button type="button" onclick="abrirModalSalvarModelo()" class="btn btn-primary mt-2 float-right">Salvar modelo de e-mail</button>
    </div>
    <div class="form-group">
        <label class="mt-5">Filtro para envio</label>
        <div class="input-group">
            <select class="form-control" name="filtro_envio" id="filtro_envio" onchange="onChangeFiltroEnvio(this)">
                <option disabled selected value> -- ESCOLHA UM FILTRO -- </option>
                <option value="users">Escolher usuários</option>
                <option value="ddd">DDD</option>
                <option value="users_month">Usuarios há mais de 1 mês se colocar saldo</option>  
                <option value="users_all">Usuários que nunca colocaram saldo</option>                              
            </select>
        </div>
    </div>

    <div style="display: none;" class="form-group" id="filtro_users">
        <a onclick="selecionarTodos('filter_users')" id='select-all'>Selecionar todos usuários</a> / 
        <a onclick="removerTodos('filter_users')" id='deselect-all'>Remover todos usuários</a>        

        <select multiple="multiple" class="my-select" id="filter_users">
            @foreach($users as $user)
                <option value="{{$user->id}}">{{ $user->email }}</option>
            @endforeach
        </select>
        <small class="text-danger" id="alerta_filtro_users"></small>
    </div>

    <div style="display: none;" class="form-group" id="filtro_ddd">
        <a onclick="selecionarTodos('filter_ddd')" id='select-all'>Selecionar todos DDD</a> / 
        <a onclick="removerTodos('filter_ddd')" id='deselect-all'>Remover todos DDD</a>        

        <select multiple="multiple" class="my-select" id="filter_ddd">
            @for($i = 11; $i <= 99; $i++)
                <option value="{{$i}}">DDD ({{$i}})</option>
            @endfor
        </select>
        <small class="text-danger" id="alerta_filtro_ddd"></small>
    </div>
    
    <div class="form-group">
        <button type="button" onclick="iniciarMKT()" class="btn btn-success">Enviar e-mails</button>
    </div>
</form>


<div class="modal" id="salvar_modelo_modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Salvar modelo de e-mail</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>  
        </div>
        <div class="modal-body">
            <div class="row" id="areaDeDecisao">
                <div class="col-12">
                    <button id="btnAtualizarModelo" onclick="atualizarModelo()" class="btn btn-primary w-100">Atualizar modelo atual</button>              
                    <button id="btnCriarModelo" onclick="criarNovoModeloArea()" class="btn btn-primary mt-2 w-100">Criar um novo modelo</button>
                </div>
            </div>
            <div class="row" style="display: none;" id="areaNovoModelo">
                <div class="col-12">
                    <div class="form-group">
                        <label>Titulo do modelo</label>
                        <input type="text" class="form-control" id="titulo_modelo" placeholder="Ex. AVISO">
                        <small class="text-danger" id="alerta_titulo_modelo"></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary" style="display: none;" onclick="criarModelo()" id="btnSalvarModelo">Salvar</button>
        </div>    
      </div>
    </div>
  </div>
  
@endsection
@section("script")
<script src="https://cdn.tiny.cloud/1/4z6pziwdxbp71pe3ypfsl52wc4ef94cm6co0pokxy1j9j1da/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.css" integrity="sha512-2sFkW9HTkUJVIu0jTS8AUEsTk8gFAFrPmtAxyzIhbeXHRH8NXhBFnLAMLQpuhHF/dL5+sYoNHWYYX2Hlk+BVHQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js" integrity="sha512-vSyPWqWsSHFHLnMSwxfmicOgfp0JuENoLwzbR+Hf5diwdYTJraf/m+EKrMb4ulTYmb/Ra75YmckeTQ4sHzg2hg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>    
    var previous_template_id = null;    
    tinymce.init({
        selector: '#template',
        plugins: 'print preview paste importcss searchreplace autolink directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
        imagetools_cors_hosts: ['picsum.photos'],
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview | insertfile image media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        image_advtab: true,
        importcss_append: true,
        file_picker_callback: function (callback, value, meta) {
            /* Provide file and text for the link dialog */
            if (meta.filetype === 'file') {
            callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
            }

            /* Provide image and alt text for the image dialog */
            if (meta.filetype === 'image') {
            callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
            }

            /* Provide alternative source and posted for the media dialog */
            if (meta.filetype === 'media') {
            callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
            }
        },
        template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
        template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
        height: 600,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_noneditable_class: 'mceNonEditable',
        toolbar_mode: 'sliding',
        contextmenu: 'link image imagetools table',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });

    $('.my-select').multiSelect()

    function selecionarTodos(id) {
        $("#"+id).multiSelect("select_all");
        return false;
    }
    function removerTodos(id) {
        $("#"+id).multiSelect("deselect_all");
    }

    function setHTMLTiny(template_id) {
        MODAL_LOADING.modal("show");
        $.ajax({
            method: "GET",
            url: "{{url("admin/template/get")}}" + "/" + template_id,
            success: (res) => {
                MODAL_LOADING.modal("hide");
                tinymce.get("template").setContent(res.model);
            }
        })
    }


    $("#template_id").on("focus", function(event) {
        previous_template_id = event.target.value;
    }).change(function(event) {
        if(!confirm("Deseja realmente alterar a template? As alterações do modelo de e-mail será perdida")) {
            $("#template_id").val(previous_template_id); 
            return;
        }
        setHTMLTiny(event.target.value);
    });

    function abrirModalSalvarModelo() {
        restaurarPadraoModal();
        let template_id = $("#template_id").val();
        $("#btnAtualizarModelo").prop("disabled", !(template_id && template_id.length > 0));
        $("#salvar_modelo_modal").modal("show");
    }

    function criarNovoModeloArea() {
        $("#areaDeDecisao").hide();
        $("#areaNovoModelo").show();
        $("#btnSalvarModelo").show();
    }

    function restaurarPadraoModal() {
        $("#areaDeDecisao").show();
        $("#areaNovoModelo").hide();
        $("#btnSalvarModelo").hide();
        $("#alerta_titulo_modelo").html("");
    }

    function criarModelo() {
        let title = $("#titulo_modelo").val();
        if(!title || title.length == 0) {
            $("#alerta_titulo_modelo").html("O titulo é um campo obrigatório");
            return;
        } else {
            $("#alerta_titulo_modelo").html("");
        }
        $.ajax({
            url: "{{url("admin/template/salvar")}}",
            method: "POST",
            data: {
                _token: "{{csrf_token()}}",
                no_redirect: true,
                title: title,
                model: tinymce.get("template").getContent()
            },
            success: (res) => {
                $("#salvar_modelo_modal").modal("hide");
                $("#template_id").append(`<option value="${res.template.id}">${res.template.title}</option>`);
                alert("Template criada com sucesso");
            },
            error: (err) => {
                $("#salvar_modelo_modal").modal("hide");
                alert("Não foi possível criar a template");
            }

        })
    }

    function atualizarModelo() {
        $.ajax({
            url: "{{url("admin/template/salvar")}}" + "/" + $("#template_id").val(),
            method: "POST",
            data: {
                _token: "{{csrf_token()}}",
                no_redirect: true,
                model: tinymce.get("template").getContent()
            },
            success: (res) => {
                $("#salvar_modelo_modal").modal("hide");
                alert("Template criada com sucesso");
            },
            error: (err) => {
                $("#salvar_modelo_modal").modal("hide");
                alert("Não foi possível criar a template");
            }

        })

    }

    function onChangeFiltroEnvio(e) {
        let filtro_atual = e.value;
        //esconder tudo
        $("#filtro_users").hide();
        $("#filtro_ddd").hide();
        if(filtro_atual === "users") {
            $("#filtro_users").show();
        }
        if(filtro_atual === "ddd") {
            $("#filtro_ddd").show();
        }
    }

    function iniciarMKT() {
        let filtro_atual = $("#filtro_envio").val();
        let assunto_email = $("#assunto_email").val();
        let alerta_users = $("#alerta_filtro_users");
        let alerta_ddd = $("#alerta_filtro_ddd");
        let alerta_assunto_email = $("#alerta_assunto_email");
        let users = [];
        let ddds = [];

        alerta_users.html("");
        alerta_ddd.html("");
        alerta_assunto_email.html("");

        if(assunto_email.length === 0) {
            alerta_assunto_email.html("O campo assunto é obrigatório");
            $("#assunto_email").focus();
            return false;
        }   
        if(!filtro_atual) {
            alert("Selecione um filtro");
            return false;
        }
        if(filtro_atual === "users") {
            users = $("#filter_users").val();
            if(users.length === 0) {
                alerta_users.html("É necessário selecionar ao menos um usuário");
                return false;
            }
        }
        if(filtro_atual === "ddd") {
            ddds = $("#filter_ddd").val();
            if(ddds.length === 0) {
                alerta_ddd.html("É necessário selecionar ao menos um DDD");
                return false;
            }
        }
        MODAL_LOADING.modal("show");
        $.ajax({
            method: "POST",
            url: "{{url("admin/marketing/email")}}",
            data: {
                _token: "{{csrf_token()}}",
                users,
                ddds,
                filtro: filtro_atual,
                assunto: assunto_email,
                template: tinymce.get("template").getContent()
            },
            success: (res) => {
                console.log(res);
                MODAL_LOADING.modal("hide");
                alert("E-mails enviado com sucesso");
            },
            error: (err) => {
                console.log(err);
                MODAL_LOADING.modal("hide");
                alert("Não foi possível enviar os e-mails");
            }
        })
    }

</script>
@endsection
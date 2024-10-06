@extends("templates.form")
@section("title")
@if(Request::is("admin/planos/editar/*"))
    Editar Plano {{$plano->nome}}
@else
    Novo Plano
@endif
@endsection
@section("form")
<form method="POST" 
enctype='multipart/form-data'
@if(Request::is("admin/planos/editar/*"))
    action="{{url("/admin/planos/salvar/".$plano->id)}}" 
@else 
    action="{{url("/admin/planos/salvar")}}" 
@endif
id="formPlano">
    @csrf
    @include("components.alert")
    <div class="form-group">
        <label for="ativo">Ativo</label>
        <input type="checkbox" name="ativo" id="ativo"
            @if(Request::is("admin/planos/editar/*"))
                @if($plano->ativo)
                    checked
                @endif
            @else
                checked
            @endif
        />
    </div>
    <div class="form-group">
        <label for="nome">Logo empresa</label>
        <br/>
        <button type="button" class="btn btn-primary mb-3" onclick="$('#file_upload').click()">Carregar logo</button>
        <input style="display: none;" id="file_upload" type="file" onchange="showPreview(event)" class="form-control" name="logo" id="logo" accept="image/*"/>
        @if($plano->logo ?? false && $plano->logo != 'noimage.png') 
            <img src="{{url("/storage/".$plano->logo)}}" style="display: block;max-width: 200px; max-height: 200px;" id="file-ip-1-preview"/>
        @else
            <img style="display: none; max-width: 200px; max-height: 200px;" id="file-ip-1-preview"/>
        @endif
    </div>
    <div class="form-group">
        <label for="nome">Nome/Empresa</label>
        <input type="text" class="form-control" name="nome" id="nome" placeholder="Ex. Unimed Saúde"
            value="{{$plano->nome ?? ""}}"
        />
    </div>
    <div class="form-group">
        <label for="telefone">Telefone</label>
        <input type="text" class="form-control" name="telefone" id="telefone" placeholder="(99) 99999-9999"
            value="{{$plano->telefone ?? ""}}"
        />
    </div>
    <div class="form-group">
        <label for="email">E-mail</label>
        <input type="text" class="form-control" name="email" id="email" placeholder="nome@email.com.br"
            value="{{$plano->email ?? ""}}"
        />
    </div>
    <button type="submit" class="btn btn-primary">
        Salvar
    </button>
    <a href="{{url("/admin/planos")}}" class="btn btn-secondary">Voltar</a>

    <br/>
    @if(isset($plano))
    <div class="float-right">
        Criado em {{$plano->created_at->format("d/m/Y")}} às {{ $plano->created_at->format("H:i:s") }}
        <br/>
        Ultima atualização em {{$plano->updated_at->format("d/m/Y")}} às {{ $plano->updated_at->format("H:i:s") }}
    </div>
    @endif

</form> 
@endsection
@section("script")
<script>
    let FORM = $("#formPlano");
    $("#telefone").mask("(99) 99999-9999");
    FORM.on("submit", (e) => {
        e.preventDefault();
        let nome = $("#nome");  
        if(isValid(nome, {name: "nome", required: true})) {
            e.currentTarget.submit();
        }
    });

    function showPreview(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("file-ip-1-preview");
            preview.src = src;
            preview.style.display = "block";
        }
    }

</script>
@endsection
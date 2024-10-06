@extends("templates.form")
@section("title")
@if(Request::is("admin/tipos/editar/*"))
    Editar Tipo {{$tipo->nome}}
@else
    Novo Tipo
@endif
@endsection
@section("form")
<form method="POST" 
@if(Request::is("admin/tipos/editar/*"))
    action="{{url("/admin/tipos/salvar/".$tipo->id)}}" 
@else 
    action="{{url("/admin/tipos/salvar")}}" 
@endif
id="formSubmit">
    @csrf
    @include("components.alert")
    <div class="form-group">
        <label for="ativo">Ativo</label>
        <input type="checkbox" name="ativo" id="ativo"
            @if(Request::is("admin/tipos/editar/*"))
                @if($tipo->ativo)
                    checked
                @endif
            @else
                checked
            @endif
        />
    </div>
    <div class="form-group">
        <label for="nome">Tipo</label>
        <input type="text" class="form-control" name="nome" id="nome" placeholder="Ex. Individual"
            value="{{$tipo->nome ?? ""}}"
        />
    </div>
    <div class="form-group">
        <label for="preco_fria">Preço leads frias</label>
        <input type="text" class="form-control" name="preco_fria" id="preco_fria" value="{{ ($tipo->preco_fria ?? false) ? number_format($tipo->preco_fria, 2) : "" }}"/>
    </div>
    <button type="submit" class="btn btn-primary">
        Salvar
    </button>
    <a href="{{url("/admin/tipos")}}" class="btn btn-secondary">Voltar</a>

    <br/>
    @if(isset($tipo))
    <div class="float-right">
        Criado em {{$tipo->created_at->format("d/m/Y")}} às {{ $tipo->created_at->format("H:i:s") }}
        <br/>
        Ultima atualização em {{$tipo->updated_at->format("d/m/Y")}} às {{ $tipo->updated_at->format("H:i:s") }}
    </div>
    @endif

</form> 
@endsection
@section("script")
<script>
    let FORM = $("#formSubmit");
    $('#preco_fria').mask('000.000.000.000.000,00', {reverse: true});

    FORM.on("submit", (e) => {
        e.preventDefault();
        let nome = $("#nome");  
        if(isValid(nome, {name: "nome", required: true})) {
            e.currentTarget.submit();
        }
    });
</script>
@endsection
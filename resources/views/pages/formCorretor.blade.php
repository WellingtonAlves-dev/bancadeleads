@extends("templates.form")
@section("title")
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Gerenciamento de Leads</a></li>
      <li class="breadcrumb-item"><a href="{{url("/corretores")}}">Meus Corretores</a></li>
      <li class="breadcrumb-item active" aria-current="page">
            @if(Request::is("corretores/editar/*"))
                Editar Corretor {{$corretor->nome}}
            @else
                Novo Corretor
            @endif
      </li>
    </ol>
</nav>
@endsection

@section("form")
<form method="POST" 
@if(Request::is("corretores/editar/*"))
    action="{{url("corretor/salvar/".$corretor->id)}}" 
@else 
    action="{{url("corretor/salvar")}}" 
@endif
id="formSubmit" class="form-horizontal shadow p-4 bg-white rounded">
    @csrf
    @include("components.alert")

    <div class="form-group row">
        <label for="ativo" class="col-sm-2 col-form-label">Ativo</label>
        <div class="col-sm-10">
            <input type="checkbox" name="ativo" id="ativo"
                @if(Request::is("corretores/editar/*"))
                    @if($corretor->ativo)
                        checked
                    @endif
                @else
                    checked
                @endif
            />
        </div>
    </div>

    <div class="form-group row">
        <label for="nome" class="col-sm-2 col-form-label">Nome</label>
        <div class="col-sm-10">
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="nome"
                placeholder="Ex. João Silva"
                value="{{$corretor->name ?? ""}}"
            />
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="email" class="col-sm-2 col-form-label">E-mail</label>
        <div class="col-sm-10">
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email"
                placeholder="Ex. contato@indicasaude.com.br"
                value="{{$corretor->email ?? ""}}"
            />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="password" class="col-sm-2 col-form-label">Senha</label>
        <div class="col-sm-10">
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password"/>
            @if(Request::is("corretores/editar/*"))
                <small class="form-text text-muted">
                    Deixe em branco se não deseja alterar a senha.
                </small>
            @endif
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-10 offset-sm-2">
            <button type="submit" class="btn btn-primary">
                Salvar
            </button>
            <a href="{{url("/corretores")}}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>

    @if(isset($corretor))
    <div class="text-right mt-3">
        <small>
            Criado em {{$corretor->created_at->format("d/m/Y")}} às {{ $corretor->created_at->format("H:i:s") }} <br/>
            Última atualização em {{$corretor->updated_at->format("d/m/Y")}} às {{ $corretor->updated_at->format("H:i:s") }}
        </small>
    </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mt-3">
            {{ implode('', $errors->all(':message')) }}
        </div>
    @endif

</form>
@endsection

@section("script")
<script>
    let FORM = $("#formSubmit");
    FORM.on("submit", (e) => {
        e.preventDefault();
        let nome = $("#nome");  
        let email = $("input#email");
        let senha = $("input#password");
        let errosCount = 0;

        !isValid(nome, {name: "Nome", required: true, minLenght: 3}) ? errosCount++ : errosCount;
        !isValid(email, {name: "E-mail", required: true, email: true}) ? errosCount++ : errosCount;
        @if(Request::is("corretores/editar/*"))
        if(senha.val().length > 0) {
            !isValid(senha, {name: "Senha", required: true, minLenght: 4}) ? errosCount++ : errosCount;
        }
        @else
            !isValid(senha, {name: "Senha", required: true, minLenght: 4}) ? errosCount++ : errosCount;
        @endif
        if(errosCount == 0) {
            e.currentTarget.submit();
        }
    });
</script>
@endsection

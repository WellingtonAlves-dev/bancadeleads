@extends("templates.form")
@section("title")
@if(Request::is("corretores/editar/*"))
    Editar corretor {{$corretor->nome}}
@else
    Novo corretor
@endif
@endsection
@section("form")
<form method="POST" 
@if(Request::is("corretores/editar/*"))
    action="{{url("corretor/salvar/".$corretor->id)}}" 
@else 
    action="{{url("corretor/salvar")}}" 
@endif
id="formSubmit">
    @csrf
    @include("components.alert")
    <div class="form-group">
        <label for="ativo">Ativo</label>
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
    <div class="form-group">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" name="name" id="nome"
                placeholder="Ex. João Silva"
                value="{{$corretor->name ?? ""}}"
            />
        </div>
        <div class="form-group">
            <label for="telefone">E-mail</label>
            <input type="text" class="form-control" name="email" id="email"
                placeholder="Ex. contato@indicasaude.com.br"
                value="{{$corretor->email ?? ""}}"
            />
        </div>
        <div class="form-group">
            <label for="password">Senha</label>
            <input type="text" class="form-control" name="password" id="password"/>
            @if(Request::is("admin/users/editar/*"))
            <small>
                Não quer mudar a senha? Deixe o campo em branco. O formulário só alterará sua senha se você preenchê-lo. Se não preencher, a senha continuará a mesma.
            </small>
            @endif
        </div>
    </div>
    <button type="submit" class="btn btn-primary">
        Salvar
    </button>
    <a href="{{url("/corretores")}}" class="btn btn-secondary">Voltar</a>

    <br/>
    @if(isset($corretor))
    <div class="float-right">
        Criado em {{$corretor->created_at->format("d/m/Y")}} às {{ $corretor->created_at->format("H:i:s") }}
        <br/>
        Ultima atualização em {{$corretor->updated_at->format("d/m/Y")}} às {{ $corretor->updated_at->format("H:i:s") }}
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
@extends("templates.form")
@section("title")
@if(Request::is("admin/users/editar/*"))
    Editar Usuário {{ucwords($user->name)}}
@else
    Novo Usuário
@endif
@endsection
@section("form")
<form method="POST" 
@if(Request::is("admin/users/editar/*"))
    action="{{url("/admin/users/salvar/".$user->id)}}" 
@else 
    action="{{url("/admin/users/salvar")}}" 
@endif
id="formSubmit">
    @csrf
    <div class="row">
        @include("components.alert")
        <div class="col-12 col-sm-1">
            <div class="form-group">
                <label for="ativo">Ativo</label>
                <input type="checkbox" name="ativo" id="ativo"
                    @if(Request::is("admin/users/editar/*"))
                        @if($user->ativo)
                            checked
                        @endif
                    @else
                        checked
                    @endif
                />
            </div>
        </div>
        <div class="col-12 col-sm-3">
            <div class="form-group">
                <label for="email_verificado">E-mail verificado</label>
                <input type="checkbox" name="email_verificado" id="email_verificado"
                    @if(Request::is("admin/users/editar/*"))
                        @if($user->email_verified_at !== null)
                            checked
                        @endif
                    @else
                        checked
                    @endif
                />
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="name" id="nome"
            placeholder="Ex. João Silva"
            value="{{$user->name ?? ""}}"
        />
    </div>
    <div class="form-group">
        <label for="telefone">E-mail</label>
        <input type="text" class="form-control" name="email" id="email"
            placeholder="Ex. contato@bancadeleads.com.br"
            value="{{$user->email ?? ""}}"
        />
    </div>
    <div class="form-group">
        <label for="telefone">telefone</label>
        <input type="text" class="form-control" name="telefone" id="telefone"
            placeholder="Ex. (11) 4215-3818"
            value="{{$user->telefone ?? ""}}"
        />
    </div>

    <div class="form-group">
        <label for="role">Permissão</label>
        <select class="form-control" name="role" id="role">
            <option value="user" @selected(($user->role ?? null) == "user" ? "checked" : "")>Corretor</option>
            <option value="admin" @selected(($user->role ?? null) == "admin" ? "checked" : "")>Admin</option>
        </select>
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

    <div class="form-group">
        <label for="observacao">Observação</label>
        <textarea class="form-control" name="observacao" id="observacao">{{$user->observacao ?? ""}}</textarea>
    </div>

    @if(Request::is("admin/users/editar/*") && ($user->role ?? null) != "admin")
        <a href="{{url("admin/users/entrar/".$user->id)}}" class="btn btn-warning">
            Entrar
        </a>
    @endif
    @if(($user->role ?? null) != "corretor" )
        <button type="submit" class="btn btn-primary">
            Salvar
        </button>
    @endif
    <a href="{{url("/admin/users")}}" class="btn btn-secondary">Voltar</a>

    @if(Request::is("admin/users/editar/*") && ($user->role ?? null) != "corretor")
        <button onclick="excluirUser()" type="button" class="btn btn-danger float-right">Excluir</button>
    @endif

    <br/>
    @if(isset($user))
    <div class="float-right">
        Criado em {{$user->created_at->format("d/m/Y")}} às {{ $user->created_at->format("H:i:s") }}
        <br/>
        Ultima atualização em {{$user->updated_at->format("d/m/Y")}} às {{ $user->updated_at->format("H:i:s") }}
    </div>
    @endif
</form> 

@if(Request::is("admin/users/editar/*") && $user->user_master === null)
    <div class="row mt-5">
        <div class="col-12">
            <h5>Saldo de contestação</h5>
            @if($saldoReposicao === null)
                <br/>
                <p>O usuário não adicionou nenhum saldo</p>
            @else
                <form class="input-group" method="POST" action="{{url("admin/saldos/reposicao/atualizar")}}">
                    @csrf
                    <input type="hidden" name="id_user" value="{{$user->id}}"/>
                    <input type="text" class="form-control" id="reposicao_saldo" placeholder="Valor do saldo de contestação" name="valor" value="{{ number_format($saldoReposicao, 2) }}"/>
                    <button class="btn btn-primary">Atualizar</button>
                </form>
                <hr/>
            @endif
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <h5>Novo saldo</h5>
            <form class="row" action="{{url("/admin/saldos/".$user->id."/novo")}}" method="POST">
                @csrf
                <div class="col-3">
                    <select class="form-control" name="tipo">
                        <option value="entrada">Entrada</option>
                        <option value="saida">Saída</option>
                    </select>
                </div>
                <div class="col-3">
                    <input placeholder="Saldo" type="text" class="form-control" name="valor" id="preco"/>
                </div>
                <div class="col-3">
                    <input 
                        placeholder="Observação" 
                        type="text" 
                        class="form-control" 
                        name="observacao" 
                        id="observacao"
                    />
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-primary">Adicionar</button>
                </div>
            </form>
            <h5 class="mt-5">Historico de saldo</h5>
            <table class="table w-100" style="font-size: 12px">
                <thead>
                    <th>
                        TIPO
                    </th>
                    <th>
                        VALOR
                    </th>
                    <th>
                        VALOR <small>(ANTERIOR)</small>
                    </th>
                    <th>
                        VALOR <small>(ATUAL)</small>
                    </th>
                    <th>
                        OBSERVAÇÃO
                    </th>
                    <th>
                        DATA/HORA
                    </th>
                </thead>
                <tbody>
                    @foreach($saldos as $saldo)
                        <tr>
                            <td>{{$saldo->tipo}}</td>
                            <td>R$ {{ number_format($saldo->valor, 2, ",", ".")}}</td>
                            <td>{{ number_format($saldo->saldo_anterior, 2, ",", ".") ?? "Sem saldo registrado" }}</td>
                            <td>{{ number_format($saldo->saldo_atual, 2, ",", ".") ?? "Sem saldo registrado" }}</td>
                            <td>{{$saldo->observação}}</td>
                            <td>{{$saldo->created_at->format("d/m/Y H:i:s")}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-end">
        {{$saldos->withQueryString()->links()}}
    </div>
@endif
@endsection
@section("script")
<script>
    let FORM = $("#formSubmit");
    $("#telefone").mask("(99) 99999-9999");
    $('#preco').mask('000.000.000.000.000,00', {reverse: true});
    $('#reposicao_saldo').mask('000.000.000.000.000,00', {reverse: true});
    FORM.on("submit", (e) => {
        e.preventDefault();
        let nome = $("#nome");  
        let email = $("input#email");
        let senha = $("input#password");
        let telefone = $("input#telefone");     
        let errosCount = 0;

        !isValid(nome, {name: "Nome", required: true, minLenght: 3}) ? errosCount++ : errosCount;
        !isValid(email, {name: "E-mail", required: true, email: true}) ? errosCount++ : errosCount;
        !isValid(telefone, {name: "Telefone", required: true, minLenght: 15}) ? errosCount++ : errosCount;
        @if(Request::is("admin/users/editar/*"))
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
    @if(Request::is("admin/users/editar/*"))
    function excluirUser() {
        let id_user = "{{$user->id}}";
        if(confirm("Deseja realmente excluir esse usuário? Essa ação será irreversivel")) {
            $.ajax({
                method: "GET",
                url: "{{url("admin/users/apagar")}}" + "/" + id_user,
                success: function(res) {
                    setTimeout(() => {
                        window.location.href = "{{url("/admin/users")}}";
                    }, 4000);
                    alert("O usuário foi removido com sucesso");
                    window.location.href = "{{url("/admin/users")}}";
                },
                error: function(err) {
                    alert("Não foi possível excluir o usuário. Entre em contato com o suporte");
                }
            })
        }
    }
    @endif
</script>
@endsection
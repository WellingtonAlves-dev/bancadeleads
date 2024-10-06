@extends("templates.form")
@section("title")
@if(Request::is("admin/leads/editar/*"))
    Editar lead {{$lead->nome}}
@else
    Nova lead
@endif
@endsection
@section("form")
<form method="POST" 
@if(Request::is("admin/leads/editar/*"))
    action="{{url("/admin/leads/salvar/".$lead->id)}}" 
@else 
    action="{{url("/admin/leads/salvar")}}" 
@endif
id="formSubmit">
    @csrf
    @include("components.alert")
    <h4>Configurações Lead:</h4>
    <div class="form-group">
        <label for="ativo">Ativo</label>
        <input type="checkbox" name="ativo" id="ativo"
            @if(Request::is("admin/leads/editar/*"))
                @if($lead->ativo)
                    checked
                @endif
            @else
                checked
            @endif
        />
        <label for="cnpj">CNPJ</label>
        <input type="checkbox" name="cnpj" id="cnpj"
        @if(Request::is("admin/leads/editar/*"))
            @if($lead->cnpj)
                checked
            @endif
        @endif
    />
    </div>

    <div class="form-group">
        <label for="preco">Preço (R$)</label>
        <input type="text" class="form-control" name="preco" id="preco" placeholder="Ex. 19,99"
            @if(isset($lead))
            value="{{ number_format($lead->preco, 2, ",", ".") }}"
            @endif
        />
    </div>

    <div class="form-group">
        <label for="dias_disponivel">Dias disponível</label>
        <input type="number" class="form-control" name="dias_disponivel" id="dias_disponivel" placeholder="Ex. 10"
            value="{{$lead->dias_disponivel ?? ""}}"
        />
    </div>

    <div class="form-group" style="display: none;">
        <label for="qtd_vidas">Quantidade vidas</label>
        <input type="number" class="form-control" value="1" name="qtd_vidas" id="qtd_vidas" placeholder="Ex. 10"
            value="{{$lead->dias_disponivel ?? ""}}"
        />
    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="plano_id">Plano</label>
                <select class="form-control" name="plano_id" id="plano_id">
                    @foreach($planos as $plano)
                        <option
                                    @if(!empty($lead))
                                        @if($lead->plano_id == $plano->id)
                                            selected
                                        @endif
                                    @endif
                        value="{{$plano->id}}"
                        >
                        {{$plano->nome}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="plano_id">Tipos</label>
                <select class="form-control" name="tipo_id" id="tipo_id">
                    @foreach($tipos as $tipo)
                        <option
                                    @if(!empty($lead))
                                        @if($lead->tipo_id == $tipo->id)
                                            selected
                                        @endif
                                    @endif
                        value="{{$tipo->id}}"
                        >
                        {{$tipo->nome}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <hr/>
    <h4>Dados do cliente: </h4>

    <div class="form-group">
        <label for="nome_lead">Nome</label>
        <input type="text" class="form-control" name="nome_lead" id="nome_lead" placeholder="Ex. João"
            value="{{$lead->nome_lead ?? ""}}"
        />
    </div>

    <div class="form-group">
        <label for="nome_lead">Perfil</label>
        <input type="text" class="form-control" name="idade" id="idade" placeholder="Ex. de 15 a 30 anos"
            value="{{$lead->idade ?? ""}}"
        />
    </div>

    <div class="row">
        <div class="col-2">
            <div class="form-group">
                <label for="ddd">DDD</label>
                <select class="form-control" name="ddd" id="ddd">
                    @for ($i = 11; $i < 100; $i++)
                        <option 
                            @if(!empty($lead))
                                @if($lead->ddd == $i)
                                    selected
                                @endif
                            @endif
                        >{{$i}}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" class="form-control" name="telefone" value="{{$lead->telefone ?? ""}}" id="telefone" placeholder="Ex. 97793-8395"/>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="email">E-mail</label>
        <input type="text" class="form-control" name="email" value="{{$lead->email ?? ""}}" id="email" placeholder="Ex. contato@indicasaude.com.br"/>
    </div>

    <div class="form-group">
        <label for="extra">Informações Extras</label>
        <textarea class="form-control" name="extra" id="extra">{{$lead->extra ?? ""}}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">
        Salvar
    </button>
    <a href="{{url("/leads")}}" class="btn btn-secondary">Voltar</a>
    @if(Request::is("admin/leads/editar/*"))
        <button onclick="excluirLead()" type="button" class="btn btn-danger float-right">Excluir</button>
    @endif
    <br/>
    @if(isset($lead))
    <div class="float-right">
        Criado em {{$lead->created_at->format("d/m/Y")}} às {{ $lead->created_at->format("H:i:s") }}
        <br/>
        Ultima atualização em {{$lead->updated_at->format("d/m/Y")}} às {{ $lead->updated_at->format("H:i:s") }}
    </div>
    @endif

</form> 
@endsection
@section("script")
<script>
    let FORM = $("#formSubmit");
    $("#telefone").mask("99999-9999");
    $('#preco').mask('000.000.000.000.000,00', {reverse: true});

    FORM.on("submit", (e) => {
        e.preventDefault();
        let preco = $("#preco");
        let dias_disponivel = $("#dias_disponivel");
        let qtd_vidas = $("#qtd_vidas");
        let nome_lead = $("#nome_lead");
        let telefone = $("#telefone");
        let qtdErros = 0;

        (!isValid(preco, {name: "Preço", required: true}) ? qtdErros++ : 0);
        (!isValid(dias_disponivel, {name: "Dias", required: true}) ? qtdErros++ : 0);
        (!isValid(qtd_vidas, {name: "Vidas", required: true}) ? qtdErros++ : 0);
        (!isValid(nome_lead, {name: "Nome", required: true}) ? qtdErros++ : 0);
        (!isValid(telefone, {name: "Telefone", required: true}) ? qtdErros++ : 0);
        if(qtdErros == 0) {
            e.currentTarget.submit();
        }
    });
    @if(Request::is("admin/leads/editar/*"))
    function excluirLead() {
        let id_lead = "{{$lead->id}}";
        if(confirm("Deseja realmente excluir essa lead? Essa ação será irreversivel")) {
            $.ajax({
                method: "GET",
                url: "{{url("admin/leads/apagar")}}" + "/" + id_lead,
                success: function(res) {
                    setTimeout(() => {
                        window.location.href = "{{url("/leads")}}";
                    }, 4000);
                    alert("A lead foi excluida com sucesso");
                    window.location.href = "{{url("/leads")}}";
                },
                error: function(err) {
                    alert("Não foi possível excluir a Lead. Entre em contato com o suporte");
                }
            })
        }
    }
    @endif
</script>
@endsection
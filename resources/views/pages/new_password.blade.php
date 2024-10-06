@extends("templates.auth")
@section("content")

<div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
            <div class="col-lg-12">
                <div class="p-5">
                    <div class="text-center">
                            <img src="{{asset("assets/img/logo.png")}}" style="height: 60px"/>
                    </div>
                    <form method="POST" action="{{url("/mail/new_password")}}" class="user" id="formLogin">
                        @csrf
                        <input type="hidden" name="unique_code" value="{{$unique_code}}"/>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input type="password" class="form-control form-control-user" id="senha"
                                name="senha"    
                                placeholder="Nova senha">
                            </div>
                            <div class="col-sm-12 mt-2">
                                <input type="password" class="form-control form-control-user" id="confirmsenha"
                                name="confirmsenha"    
                                placeholder="Confirmar nova senha">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Alterar senha
                        </button>
                    </form>
                    @if(Session::has("success"))
                        <div class="alert alert-warning mt-3 mb-3">
                            {{Session::get("success")}}
                        </div>
                    @endif
                    <hr>
                    <div class="text-center">
                        <a class="small" href="{{url("/login")}}">Voltar para a página de login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<script src="{{asset("assets/js/validateInput.js")}}"></script>
<script>
    const FORM = $("#formLogin");
    FORM.on("submit", (e) => {
        e.preventDefault();
        let senha = $("#senha");
        let confirmsenha = $("#confirmsenha");
        let countErros = 0;
        !isValid(senha, {name: "Senha", required: true, minLenght: 4}) ? countErros++ : countErros;
        if(confirmsenha.val() != senha.val()) {
            alert("As senhas precisam ser iguais");
            return;
        }
        if(countErros == 0) {
            e.currentTarget.submit();
        }
    });
</script>
@endsection
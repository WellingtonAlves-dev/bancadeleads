@extends("templates.auth")
@section("content")

<style>
    @media screen and (max-width: 728px) {
        .container {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    }

</style>

<div class="d-flex align-items-center justify-content-center mt-5">
    <img src="{{asset("assets/img/logo.png")}}" style="height: 60px"/>
</div>


<div class="container">
    <div class="card o-hidden border-0 my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h3 class="h3 white font-weight-bold mb-4">Criar uma conta</h1>
                        </div>
                        <form method="POST" class="user" action="{{url("/signup")}}" id="formSignup">
                            @csrf
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="name"
                                    name="name"    
                                    placeholder="Nome">
                                </div>
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="telefone"
                                    name="telefone"
                                    placeholder="Telefone">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="email" class="form-control form-control-user" id="email"
                                    name="email"
                                    placeholder="E-mail">
                                </div>
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="email" class="form-control form-control-user" id="confirmemail"
                                    name="confirmemail"
                                    placeholder="Repita seu e-mail">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user"
                                        name="password"
                                        id="password" placeholder="Senha">
                                    <small class="">A senha precisa ter ao menos 6 caracteres</small>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user"
                                        id="passwordConfirm" placeholder="Repita a Senha">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input id="termosprivacidade" type="checkbox"/>
                                    <label for="termosprivacidade">Eu aceito os <a target="_blank"  href="https://indicasaude.com.br/termos.html">termos e a política de privacidade</a> do site.</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Registrar Conta
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small " href="{{url("/login")}}">Já tem uma conta? Entre!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal_termos" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">termos e a política de privacidade</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="max-height: 300px; overflow: auto;">
        <p>
            Termo de Uso do Site "INDICA SAÚDE"

            
        </p>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>


@endsection

@section("script")
<script src="{{asset("assets/js/validateInput.js")}}"></script>
<script>
    const FORM = $("form#formSignup");
    FORM.on("submit", validateSubmit);
    $("#telefone").mask("(99) 99999-9999");

    function validateSubmit(e) {
        e.preventDefault();
        if($("#termosprivacidade").prop("checked") === false) {
            return alert("Para se cadastrar ao sistema é necessário aceitar os termos e a política de privacidade");
        }
        let nome = $("input#name");
        let email = $("input#email");
        let senha = $("input#password");
        let telefone = $("input#telefone");
        let confirmarSenha = $("input#passwordConfirm");
        let confirmEmail = $("#confirmemail");
        let errosCount = 0;

        !isValid(nome, {name: "Nome", required: true, minLenght: 3}) ? errosCount++ : errosCount;
        !isValid(email, {name: "E-mail", required: true, email: true}) ? errosCount++ : errosCount;
        !isValid(senha, {name: "Senha", required: true, minLenght: 4}) ? errosCount++ : errosCount;
        !isValid(telefone, {name: "Telefone", required: true, minLenght: 15}) ? errosCount++ : errosCount;
        if(senha.val() != confirmarSenha.val()) {
            confirmarSenha.addClass("is-invalid");
            errosCount++;
        } else {
            confirmarSenha.removeClass("is-invalid");
        }
        if(confirmEmail.val() != email.val()) {
            errosCount++;
            confirmEmail.addClass("is-invalid");
        } else {
            confirmEmail.removeClass("is-invalid");
        }
        
        if(errosCount == 0) {
            e.currentTarget.submit();
        }
    }

</script>
@endsection
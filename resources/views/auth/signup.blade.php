@extends("templates.auth")
@section("content")

<style>

    /* Centralizando o conteúdo da página */
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        /* margin-top: 30px; */
        width: 100%;
        min-height: 100vh;
        background-color: #007fff;
    }

    .container-info {
        width: 100%;
        max-width: 500px;
    }

    /* Card estilizado para o formulário */
    .form-card {
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        padding: 30px;
        background-color: white;
        border-radius: 10px;
    }

    /* Ocultar texto de informações em telas menores */
    @media screen and (max-width: 1000px) {
        .infos-text {
            display: none;
        }
    }

    /* Tornar o container responsivo */
    @media screen and (max-width: 728px) {
        .container-info {
            width: 100%;
        }
    }

</style>

<div class="d-flex align-items-center justify-content-center mt-5">
    <img src="{{asset('assets/img/logo.png')}}" style="height: 60px" />
</div>

<div class="container">
    <div class="card o-hidden border-0 my-5">
        <div class="card-body p-0" style="background-color: white;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h3 class="h3 white font-weight-bold mb-4">Criar uma conta</h3>
                        </div>
                        <form method="POST" class="user" action="{{url('/signup')}}" id="formSignup">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="name" name="name"
                                    placeholder="Nome">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="telefone" name="telefone"
                                    placeholder="Telefone">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="email" name="email"
                                    placeholder="E-mail">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="confirmemail" name="confirmemail"
                                    placeholder="Repita seu e-mail">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user" id="password" name="password"
                                    placeholder="Senha">
                                <small>A senha precisa ter ao menos 6 caracteres</small>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user" id="passwordConfirm"
                                    placeholder="Repita a Senha">
                            </div>
                            <div class="form-group">
                                <input id="termosprivacidade" type="checkbox" />
                                <label for="termosprivacidade">Eu aceito os <a target="_blank"
                                        href="https://indicasaude.com.br/termos.html">termos e a política de privacidade</a>
                                    do site.</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Registrar Conta
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="{{url('/login')}}">Já tem uma conta? Entre!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{asset('assets/js/validateInput.js')}}"></script>
<script>
    const FORM = $("form#formSignup");
    FORM.on("submit", validateSubmit);
    $("#telefone").mask("(99) 99999-9999");

    function validateSubmit(e) {
        e.preventDefault();
        if ($("#termosprivacidade").prop("checked") === false) {
            return alert("Para se cadastrar ao sistema é necessário aceitar os termos e a política de privacidade");
        }
        let nome = $("input#name");
        let email = $("input#email");
        let senha = $("input#password");
        let telefone = $("input#telefone");
        let confirmarSenha = $("input#passwordConfirm");
        let confirmEmail = $("#confirmemail");
        let errosCount = 0;

        !isValid(nome, { name: "Nome", required: true, minLenght: 3 }) ? errosCount++ : errosCount;
        !isValid(email, { name: "E-mail", required: true, email: true }) ? errosCount++ : errosCount;
        !isValid(senha, { name: "Senha", required: true, minLenght: 4 }) ? errosCount++ : errosCount;
        !isValid(telefone, { name: "Telefone", required: true, minLenght: 15 }) ? errosCount++ : errosCount;
        if (senha.val() != confirmarSenha.val()) {
            confirmarSenha.addClass("is-invalid");
            errosCount++;
        } else {
            confirmarSenha.removeClass("is-invalid");
        }
        if (confirmEmail.val() != email.val()) {
            errosCount++;
            confirmEmail.addClass("is-invalid");
        } else {
            confirmEmail.removeClass("is-invalid");
        }

        if (errosCount == 0) {
            e.currentTarget.submit();
        }
    }
</script>
@endsection

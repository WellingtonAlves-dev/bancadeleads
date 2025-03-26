@extends("templates.auth")
@section("content")
<title>Banca de Leads - Cadastre-se</title>

<style>
    body {
        font-family: 'Lato', sans-serif;
        background-color: #007fff;
        margin: 0;
        padding: 0;
    }

    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
        flex-direction: column;
    }

    .login-container img {
        height: 80px;
        margin-bottom: 20px;
    }

    .login-box {
        width: 100%;
        max-width: 500px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .login-body {
        padding: 30px;
    }

    .login-title {
        text-align: center;
        color: #333;
        margin-bottom: 25px;
        font-size: 22px;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 20px;
        position: relative;
    }

    .form-control {
        height: 50px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding-left: 15px;
        font-size: 15px;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: #007fff;
        box-shadow: 0 0 0 3px rgba(0, 127, 255, 0.2);
    }

    .small-text {
        font-size: 13px;
        color: #666;
        margin-top: 5px;
        display: block;
    }

    /* BOTÕES NO PADRÃO DA TELA DE LOGIN */
    .btn {
        height: 50px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 20px;
    }

    .btn-primary {
        background-color: #007fff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #006dd9;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-block {
        display: flex;
        width: 100%;
    }

    .btn i {
        margin-right: 8px;
        font-size: 18px;
    }

    .terms-checkbox {
        display: flex;
        align-items: center;
        margin: 20px 0;
    }

    .terms-checkbox input {
        margin-right: 10px;
    }

    .terms-checkbox label {
        color: #666;
        font-size: 14px;
    }

    .terms-checkbox a {
        color: #007fff;
        text-decoration: none;
    }

    .terms-checkbox a:hover {
        text-decoration: underline;
    }

    .divider {
        margin: 20px 0;
        border-top: 1px solid #e0e0e0;
    }

    .login-footer {
        text-align: center;
        margin-top: 15px;
        color: #666;
        font-size: 14px;
    }

    .login-footer a {
        color: #007fff;
        text-decoration: none;
        font-weight: 600;
    }

    .login-footer a:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .login-body {
            padding: 25px 20px;
        }
        
        .btn {
            height: 45px;
            font-size: 15px;
        }
    }
</style>

<div class="login-container">
    <img src="{{asset('assets/img/logo.png')}}" alt="Logo">
    <div class="login-box">
        <div class="login-body">
            <h2 class="login-title">Criar uma conta</h2>

            <form method="POST" action="{{url('/signup')}}" id="formSignup">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nome" required>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Telefone" required>
                </div>

                <div class="form-group">
                    <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
                </div>

                <div class="form-group">
                    <input type="email" class="form-control" id="confirmemail" name="confirmemail" placeholder="Repita seu e-mail" required>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Senha" required>
                    <span class="small-text">A senha precisa ter ao menos 6 caracteres</span>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" id="passwordConfirm" placeholder="Repita a Senha" required>
                </div>

                <div class="terms-checkbox">
                    <input type="checkbox" id="termosprivacidade" required>
                    <label for="termosprivacidade">
                        Eu aceito os <a target="_blank" href="https://bancadeleads.com.br/sistema/termos.php">termos e a política de privacidade</a> do site.
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fa fa-user-plus"></i> Registrar Conta
                </button>

                <div class="divider"></div>

                <div class="login-footer">
                    <a href="{{url('/login')}}">Já tem uma conta? Entre!</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{asset('assets/js/validateInput.js')}}"></script>
<script>
    const FORM = $("#formSignup");
    FORM.on("submit", validateSubmit);
    $("#telefone").mask("(99) 99999-9999");

    function validateSubmit(e) {
        e.preventDefault();
        if (!$("#termosprivacidade").prop("checked")) {
            alert("Para se cadastrar ao sistema é necessário aceitar os termos e a política de privacidade");
            return false;
        }

        let nome = $("#name");
        let email = $("#email");
        let senha = $("#password");
        let telefone = $("#telefone");
        let confirmarSenha = $("#passwordConfirm");
        let confirmEmail = $("#confirmemail");
        let errosCount = 0;

        !isValid(nome, { name: "Nome", required: true, minLenght: 3 }) ? errosCount++ : errosCount;
        !isValid(email, { name: "E-mail", required: true, email: true }) ? errosCount++ : errosCount;
        !isValid(senha, { name: "Senha", required: true, minLenght: 6 }) ? errosCount++ : errosCount;
        !isValid(telefone, { name: "Telefone", required: true, minLenght: 15 }) ? errosCount++ : errosCount;
        
        if (senha.val() != confirmarSenha.val()) {
            confirmarSenha.addClass("is-invalid");
            errosCount++;
        } else {
            confirmarSenha.removeClass("is-invalid");
        }
        
        if (confirmEmail.val() != email.val()) {
            confirmEmail.addClass("is-invalid");
            errosCount++;
        } else {
            confirmEmail.removeClass("is-invalid");
        }

        if (errosCount == 0) {
            e.currentTarget.submit();
        }
    }
</script>
@endsection
@extends("templates.auth")
@section("header")
<title>Banca de Leads - Entrar</title>
@endsection
@section("content")
<style>
    body {
        font-family: 'Lato', sans-serif;
        background-color: #007fff;
        overflow-y: hidden;
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
        max-height: 160px;
        margin-bottom: 12px;
    }

    .login-box {
        width: 100%;
        max-width: 400px;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .login-header {
        background: #007fff;
        padding: 25px;
        text-align: center;
    }

    .login-header img {
        height: 60px;
        filter: brightness(0) invert(1);
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
        padding-left: 45px;
        font-size: 15px;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: #007fff;
        box-shadow: 0 0 0 3px rgba(0, 127, 255, 0.2);
    }

    .form-control-feedback {
        position: absolute;
        top: 15px;
        left: 15px;
        color: #888;
        font-size: 18px;
    }

    .btn {
        height: 50px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary {
        background-color: #007fff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #006dd9;
        transform: translateY(-2px);
    }

    .btn-default {
        background-color: #f8f9fa;
        color: #333;
        border: 1px solid #e0e0e0;
    }

    .btn-default:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
    }

    .login-footer {
        text-align: center;
        margin-top: 25px;
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

    .divider {
        display: flex;
        align-items: center;
        margin: 20px 0;
        color: #999;
        font-size: 14px;
    }

    .divider::before, .divider::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid #e0e0e0;
    }

    .divider::before {
        margin-right: 15px;
    }

    .divider::after {
        margin-left: 15px;
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
            <h2 class="login-title">Entre para iniciar sua sessão</h2>
            
            @if(Session::get('cadastro_sucesso'))
                <div class="alert alert-success text-center mb-4">
                    Seu cadastro foi realizado com sucesso!
                </div>
            @endif
            
            @if(Session::get('mail_verify'))
                <div class="alert alert-success text-center mb-4">
                    Seu e-mail foi verificado com sucesso!
                </div>
            @endif
            
            @if(Session::get('password_reset'))
                <div class="alert alert-success text-center mb-4">
                    Sua senha foi alterada com sucesso!
                </div>
            @endif
            
            @if(Session::get('nao_verificado'))
                <div class="alert alert-danger text-center mb-4">
                    Verifique sua caixa de e-mail para confirmar a verificação.
                </div>
            @endif
            
            @if(Session::get('nao_cadastrado'))
                <div class="alert alert-danger text-center mb-4">
                    Credenciais inválidas. Verifique e tente novamente.
                </div>
            @endif

            <form method="POST" action="{{url('/login')}}" id="formLogin">
                @csrf
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                    <span class="fa fa-envelope form-control-feedback"></span>
                </div>
                
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Senha" name="password" required>
                    <span class="fa fa-lock form-control-feedback"></span>
                </div>
                
                <div class="row w-100 d-flex justify-content-between">
                    <div class="col-6" style="padding: 5px;">
                        <a href="{{url('/signup')}}" class="btn btn-default btn-block">
                            <i class="fa fa-user-plus" style="margin-right: 8px;"></i> Cadastre-se
                        </a>
                    </div>
                    <div class="col-6" style="padding: 5px;">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa fa-sign-in" style="margin-right: 8px;"></i> Entrar
                        </button>
                    </div>
                </div>
                
                <div class="divider">ou</div>
                
                <div class="login-footer">
                    <a href="{{url('/recovery')}}">Esqueceu sua senha?</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section("script")
<script src="{{asset('assets/js/validateInput.js')}}"></script>
<script>
    const FORM = $("#formLogin");
    FORM.on("submit", (e) => {
        e.preventDefault();
        let email = $("input[name='email']");
        let senha = $("input[name='password']");
        let countErros = 0;
        
        if(!isValid(email, {name: "e-mail", required: true, type: "email"})) {
            countErros++;
        }
        
        if(!isValid(senha, {name: "senha", required: true})) {
            countErros++;
        }
        if(countErros === 0) {
            e.currentTarget.submit();
        }
    });
</script>
@endsection
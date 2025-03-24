@extends("templates.auth")
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

    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeeba;
        color: #856404;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
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
            <h2 class="login-title">Recuperação de Senha</h2>
            
            @if(Session::has('success'))
                <div class="alert alert-warning mb-4">
                    {{Session::get('success')}}
                </div>
            @endif

            <form method="POST" action="{{url('/mail/recovery')}}" id="formLogin">
                @csrf
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Digite seu e-mail" name="email" required>
                    <span class="fa fa-envelope form-control-feedback"></span>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fa fa-paper-plane" style="margin-right: 8px;"></i> Enviar é-mail de recuperação
                </button>
                
                <div class="login-footer">
                    <a href="{{url('/login')}}">Voltar para a página de login</a>
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
        let countErros = 0;
        
        if(!isValid(email, {name: "e-mail", required: true, type: "email"})) {
            countErros++;
        }
        
        if(countErros === 0) {
            e.currentTarget.submit();
        }
    });
</script>
@endsection
@extends("templates.auth")
@section("content")

<style>

    /* Centralizando o conteúdo da página */
    .container-central {
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

<div class="container-central">
    <div class="container-info">

        <!-- Logo centralizado -->
        <div class="text-center mb-5">
            <img src="{{asset('assets/img/logo.png')}}" style="height: 60px" alt="Logo"/>
        </div>

        <!-- Card com o formulário de login -->
        <div class="form-card">
            <div class="text-center">
                <h3 class="h3 mb-4 font-weight-bold">Bem-vindo de volta!</h3>
                <p class="text-muted">Por favor, insira suas credenciais para acessar sua conta.</p>
            </div>

            <form method="POST" action="{{url('/login')}}" id="formLogin">
                @csrf
                <!-- Campo de E-mail -->
                <div class="form-group mb-4">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Digite seu e-mail">
                </div>

                <!-- Campo de Senha -->
                <div class="form-group mb-4">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha">
                </div>

                <!-- Botão de Login -->
                <button type="submit" class="btn btn-primary btn-block w-100">
                    Acessar minha conta
                </button>

                <!-- Links adicionais -->
                <div class="mt-4">
                    <p class="text-center">
                        <a href="{{url('/recovery')}}" class="text-primary small">Esqueceu sua senha?</a>
                    </p>
                    <p class="text-center">
                        <a href="{{url('/signup')}}" class="text-primary small">Ainda não tem conta? Cadastre-se</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Exibição de mensagens de sucesso e erro -->
        @if(Session::get('cadastro_sucesso'))
        <div class="alert alert-success text-center mt-4">
            <p>Seu cadastro foi realizado com sucesso!</p>
        </div>
        @endif

        @if(Session::get('mail_verify'))
        <div class="alert alert-success text-center mt-4">
            <p>Seu e-mail foi verificado com sucesso!</p>
        </div>
        @endif

        @if(Session::get('password_reset'))
        <div class="alert alert-success text-center mt-4">
            <p>Sua senha foi alterada com sucesso!</p>
        </div>
        @endif

        @if(Session::get('nao_verificado'))
        <div class="alert alert-danger text-center mt-4">
            <p>Verifique sua caixa de e-mail para confirmar a verificação. Caso não tenha recebido, entre em contato com o suporte.</p>
        </div>
        @endif

        @if(Session::get('nao_cadastrado'))
        <div class="alert alert-danger text-center mt-4">
            <p>Credenciais inválidas. Verifique e tente novamente.</p>
        </div>
        @endif
    </div>
</div>

@endsection

@section("script")
<script src="{{asset('assets/js/validateInput.js')}}"></script>
<script>
    const FORM = $("#formLogin");
    FORM.on("submit", (e) => {
        e.preventDefault();
        let email = $("#email");
        let senha = $("#password");
        let countErros = 0;
        if(!isValid(email, {name: "e-mail", required: true})) {
            countErros++;
        }
        if(!isValid(senha, {name: "senha", required: true})) {
            countErros++;
        }
        console.log(countErros);
        if(countErros == 0) {
            e.currentTarget.submit();
        }
    });
</script>
@endsection

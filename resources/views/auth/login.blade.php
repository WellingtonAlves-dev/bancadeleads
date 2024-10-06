@extends("templates.auth")
@section("content")

<style>


    .container-central {
        display: flex;
        justify-content: space-around;
        margin-top: 30px;
        width: 100%;
    }

    .container-info {
        width: 80%;
    }
    

    .form-card {
        width: 50%
    }

    @media screen and (max-width: 1000px) {
        .infos-text {
            display: none;
        }
        .form-card {
            width: 100%;
        }
    }

    @media screen and (max-width: 728px) {
        .container-info {
            width: 100%;
        }
    }

</style>

<div class="container-central">
    <div class="container-info">
        <div class="d-flex align-items-center justify-content-center mt-3">
            <img src="{{asset("assets/img/logo.png")}}" style="height: 60px"/>
        </div>

        <div class="d-flex justify-content-around" style="margin-top: 80px">
            <div class="form-card">


                <div class="card o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-lg-5 p-4">
                                    <div class="text-center">
                                        <h3 class="h3 white font-weight-bold mb-4">Faça o seu login</h1>
                                    </div>
                                    {{-- <div class="text-center">
                                        <img src="{{asset("assets/img/logo.png")}}" style="width: 150px"/>
                                    </div> --}}
                                    <form method="POST" class="user" action="{{url("/login")}}" id="formLogin">
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-sm-12 mb-3 mb-sm-0">
                                                <input type="text" class="form-control form-control-user" id="email"
                                                name="email"    
                                                placeholder="E-mail">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12 mb-3 mb-sm-0">
                                                <input class="form-control form-control-user" id="password"
                                                name="password"
                                                type="password"    
                                                placeholder="Senha">
                                            </div>
                                        </div>

                                        <div class="container-fluid">
                                            <div class="row ">
                                                <div class="col-lg-12 ml-auto">
                                                    <button type="submit" class="btn btn-primary btn-user btn-block w-100">
                                                        Entrar
                                                    </button>
                                                </div>
            <!-- Divider -->
            <hr class="sidebar-divider mt-5"/>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="text-start">
                                                        <a class="small " href="{{url("/signup")}}">Ainda não possui cadastro? Cadastre aqui!</a>
                                                    </div>
                                                    <div class="text-start">
                                                        <a class="small " href="{{url("/recovery")}}">Esqueceu a senha? Recupere aqui!</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </form>
                                </div>
                                @if(Session::get("cadastro_sucesso"))
                                
                                <div class="alert alert-success mx-5 text-center">
                                    <p>
                                        Cadastro Realizado com sucesso
                                    </p>
                                    {{-- <strong>
                                        Um e-mail de confirmação foi enviado para sua caixa postal
                                    </strong> --}}
                                </div>
                                
                                @endif
                                @if(Session::get("mail_verify"))
                                
                                <div class="alert alert-success mx-5 text-center">
                                    <p>
                                        Parábens!!! Seu e-mail foi verificado com sucesso
                                    </p>
                                </div>
                                
                                @endif
                
                                @if(Session::get("password_reset"))
                                
                                <div class="alert alert-success mx-5 text-center">
                                    <p>
                                        Sua senha foi alterada com sucesso!!!
                                    </p>
                                </div>
                                
                                @endif
                
                
                                @if(Session::get("nao_verificado"))
                                <div class="alert alert-danger mx-5 text-center">
                                    <p>
                                        Ops! Verifique sua caixa de e-mail para saber se e-mail foi verificado.
                                        Caso não tenha recebido o e-mail, entre em contato com o suporte.
                                    </p>
                                </div>
                                @endif
                
                                @if(Session::get("nao_cadastrado"))
                                <div class="alert alert-danger mx-5 text-center">
                                    <p>
                                        O e-mail ou senha estão incorreto!
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
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
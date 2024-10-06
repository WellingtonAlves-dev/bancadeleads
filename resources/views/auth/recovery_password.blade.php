@extends("templates.auth")
@section("content")


<div class="d-flex align-items-center justify-content-center mt-5">
    <img src="{{asset("assets/img/logo.png")}}" style="height: 60px"/>
</div>

<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h3 class="h3 white font-weight-bold mb-4">Recuperação de Senha</h1>
                        </div>
                        <form method="POST" class="user" action="{{url("/mail/recovery")}}" id="formLogin">
                            @csrf
                            <div class="form-group row">
                                <div class="col-sm-12 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="email"
                                    name="email"    
                                    placeholder="Digite seu e-mail">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Enviar código de recuperação
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
        console.log(countErros);
        if(countErros == 0) {
            e.currentTarget.submit();
        }
    });
</script>
@endsection
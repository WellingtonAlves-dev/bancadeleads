@extends("templates.default")
@section("content")

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0  font-weight-bold">Perfil</h1>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow p-3">
                                <h4>Informações</h4>
                                <form class="mt-2" action="#">
                                    <div class="form-group">
                                        <label>Nome</label>
                                        <input class="form-control" value="{{ucwords(Auth::user()->name)}}" disabled/>
                                    </div>
                                    <div class="form-group">
                                        <label>E-mail</label>
                                        <input class="form-control" value="{{ucwords(Auth::user()->email)}}" disabled/>
                                    </div>
                                    <div class="form-group">
                                        <label>Telefone</label>
                                        <input class="form-control" value="{{ucwords(Auth::user()->telefone)}}" disabled/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card shadow p-3">
                                <h4>Alterar Senha</h4>
                                <form class="mt-2">
                                    <div class="form-group">
                                        <input placeholder="Senha antiga" type="password" class="form-control" name="password_old" id="password_old"/>
                                    </div>
                                    <div class="form-group">
                                        <input placeholder="Nova senha" type="password" class="form-control" name="password" id="password"/>
                                    </div>
                                    <div class="form-group">
                                        <input placeholder="Confirmar nova senha" type="password" class="form-control" name="confirm_password" id="confirm_password"/>
                                    </div>

                                    <button class="btn btn-primary">Alterar</button>
                                </form>
                            </div>
                        </div>
                    </div>

@endsection
@extends("templates.list")
@section("title")
    Usuários
@endsection
@section("menu")
    <a href="{{url("/admin/users/novo")}}" class="btn btn-primary">Novo Usuário</a>
    <div class="row mt-3 justify-content-end">
        <div class="col-4">
            <form method="GET" url="{{url("admin/users")}}">
                <div class="input-group ">
                    <input type="text" value="{{request()->get("search")}}" class="form-control" name="search" placeholder="Pesquisar nome ou email">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                </div>
                  

            </form>
        </div>
    </div>
@endsection
@section("tabela")
<table class="table table-bordered mt-3" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>Ativo</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th>Permissão</th>
            <th>Lider</th>
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Ativo</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th>Permissão</th>
            <th>Lider</th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($users as $user)
            <tr
                data-type
                data-name="{{$user->name}}"
                data-email="{{$user->email}}"
            >
                <td>{{$user->id}}</td>
                <td>{{$user->ativo ? "Ativo" : "Inativo"}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->telefone}}</td>
                <td>{{ucwords($user->role)}}</td>
                <td>{!!$user->user_master ? "<a href='".url('admin/users/editar/'.$user->user_master)."'>{$user->user_master}</a>" : "Usuário não é dependente"!!}</td>
                <td>
                    <a href="{{url("/admin/users/editar/".$user->id)}}" class="btn btn-primary btn-sm">
                        <i class="fas fa-pen"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{$users->onEachSide(0)->withQueryString()->links()}}
</div>
@endsection
@section("script")
@endsection
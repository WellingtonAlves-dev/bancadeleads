@extends("templates.list")
@section("title")
Notificações
@endsection
@section("menu")
    <form method="GET" class="row justify-content-end w-100">
        <div class="col-5">
            <div class="input-group">
                <input type="text" value="{{request()->get("id")}}" name="id" class="form-control" placeholder="Pesquisar ID DE PREFERENCIA/ID_PAYMENT">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
            </div>
        </div>
    </form>
@endsection
@section("tabela")
<table class="table" style="font-size: 12px">
    <thead>
        <tr>
            <th>USUÁRIO</th>
            <th>ID DE PREFERENCIA</th>
            <th>ID PAYMENT</th>
            <th>STATUS</th>
            <th>CRIADO</th>
            <th>ATUALIZADO</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($notificacoes as $notificacao)
            <tr>
                <td>
                    <a href="{{url("admin/users/editar/".$notificacao->user_id)}}">{{$notificacao->user_name}}</a>
                </td>
                <td>
                    {{$notificacao->preference_id}}
                </td>
                <td>
                    {{$notificacao->id_payment}}
                </td>
                <td>
                    {{$notificacao->status}}
                </td>
                <td>
                    {{$notificacao->created_at}}
                </td>
                <td>
                    {{$notificacao->updated_at}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{$notificacoes->onEachSide(0)->withQueryString()->links()}}
</div>
@endsection
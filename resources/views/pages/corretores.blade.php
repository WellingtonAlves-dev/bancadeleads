@extends("templates.list")
@section("title")
Meus Corretores
@endsection
@section("menu")
    <a class="btn btn-primary" href="{{url("corretores/novo")}}">Novo corretor</a>
@endsection
@section("tabela")
<table class="table table-bordered" style="font-size: 11px" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th>NOME</th>
            <th>E-MAIL</th>
            <th>ATIVO</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($corretores as $corretor)
            <tr>
                <td>{{$corretor->id}}</td>
                <td>{{$corretor->name}}</td>
                <td>{{$corretor->email}}</td>
                <td>{{$corretor->ativo ? "SIM" : "NÃO"}}</td>
                <td>
                    <a href="{{url("corretores/editar/".$corretor->id)}}" class="btn btn-light btn-sm">
                        <i class="fas fa-pen"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
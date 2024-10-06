@extends("templates.list")
@section("title")
Marketing <small>(e-mail)</small>
@endsection
@section("menu")
    <a href="{{url("admin/template/novo")}}" class="btn btn-sm btn-secondary float-right" onclick="novoTemplate()">Novo template</a>
@endsection
@section("tabela")

<table class="table table-bordered" style="font-size: 12px" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>#</th>
            <th style="width: 80%">TITULO</th>
            <th>AÇÕES</th>
        </tr>
    </thead>
    <tbody>
        @foreach($templates as $template)
            <tr>
                <td>{{ $template->id }}</td>
                <th>{{ $template->title }}</th>
                <td>
                    <button onclick="deleteTemplate('{{$template->id}}', ' {{$template->title}} ')" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                    <a href="{{url("admin/template/editar/".$template->id)}}" class="btn btn-warning btn-sm">
                        <i class="fas fa-pen"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
@section("script")
<script type="text/javascript">
    function deleteTemplate(id, title) {
        if(confirm("Deseja realmente apagar a template " + title)) {
            window.location.href = "{{url("admin/template/apagar")}}" + "/" + id;
        }
    }
</script>
@endsection
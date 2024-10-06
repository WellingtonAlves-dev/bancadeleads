@extends("templates.form")
@section("title")
@if(Request::is("admin/template/editar/*"))
    Editar template {{$template->title}}
@else
    Nova template
@endif
@endsection
@section("form")
<form method="POST" 
    @if(isset($template))
    action="{{url("admin/template/salvar/".$template->id)}}"
    @else
    action="{{url("admin/template/salvar")}}"
    @endif>
    @csrf
    <div class="form-group">
        <label>Titulo</label>
        <input type="text" class="form-control" required name="title" value="{{$template->title ?? ""}}"/>
    </div>
    <div class="form-group">
        <textarea id="template" name="model">{!! $template->model ?? "" !!}</textarea>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{url("/admin/marketing/templates")}}" class="btn btn-secondary">Voltar</a>
    </div>
</form>
@endsection
@section("script")
<script src="https://cdn.tiny.cloud/1/4z6pziwdxbp71pe3ypfsl52wc4ef94cm6co0pokxy1j9j1da/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>    
    tinymce.init({
      selector: '#template'
    });
</script>
@endsection
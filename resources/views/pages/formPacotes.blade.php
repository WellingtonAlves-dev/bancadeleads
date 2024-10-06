@extends("templates.form")
@section("title")
@if(Request::is("admin/pacotes/editar/*"))
    Editar Pacote
@else
    Novo Pacote
@endif
@endsection
@section("form")
<form method="POST" 
id="formSubmit"
@if(Request::is("admin/pacotes/editar/*"))
    action="{{url('/admin/pacotes/salvar/'.$pacote->id)}}"
@else
    action="{{url('/admin/pacotes/salvar')}}"
@endif
>
    @csrf
    @include("components.alert")
    <div class="form-group">
        <label for="ativo">Ativo</label>
        <input type="checkbox" name="ativo" id="ativo"
            @if(Request::is("admin/pacotes/editar/*"))
                @if($pacote->ativo)
                    checked
                @endif
            @else
                checked
            @endif
        />
    </div>
    <div class="form-group">
        <label for="name">Nome</label>
        <input type="text" class="form-control @error("name") is-invalid @enderror" name="name" placeholder="Ex. BÁSICO" value="{{$pacote->name ?? ""}}"/>
        @error("name")
            <small class="text-danger">{{$message}}</small>
        @enderror
    </div>  
    <div class="form-group">
        <label for="qtd_leads">Quantidade de leads</label>
        <input type="number" class="form-control  @error("qtd_leads") is-invalid @enderror" name="qtd_leads" placeholder="Ex. 5" value="{{$pacote->qtd_leads ?? ""}}"/>
        @error("qtd_leads")
            <small class="text-danger">{{$message}}</small>
        @enderror
    </div>
    <div class="form-group">
        <label for="qtd_desconto">Quantidade de desconto</label>
        <input type="number" class="form-control @error("qtd_desconto") is-invalid @enderror" name="qtd_desconto" placeholder="Ex. 10%" value="{{$pacote->qtd_desconto ?? ""}}"/>
        @error("qtd_desconto")
            <small class="text-danger">{{$message}}</small>
        @enderror
    </div>
    <div class="form-group">
        <label for="descricao">Descrição</label>
        <textarea name="descricao" id="template">{{$pacote->descricao ?? ""}}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">
        Salvar
    </button>
    <a href="{{url("/admin/pacotes")}}" class="btn btn-secondary">Voltar</a>

    <br/>


    @if(isset($pacote))
        <div class="float-right">
            Criado em {{$pacote->created_at->format("d/m/Y")}} às {{ $pacote->created_at->format("H:i:s") }}
            <br/>
            Ultima atualização em {{$pacote->updated_at->format("d/m/Y")}} às {{ $pacote->updated_at->format("H:i:s") }}
        </div>
    @endif

</form> 
@endsection
@section("script")
<script src="https://cdn.tiny.cloud/1/4z6pziwdxbp71pe3ypfsl52wc4ef94cm6co0pokxy1j9j1da/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
        tinymce.init({
        selector: '#template',
        plugins: 'print preview paste importcss searchreplace autolink directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
        imagetools_cors_hosts: ['picsum.photos'],
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview | insertfile image media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        image_advtab: true,
        importcss_append: true,
        file_picker_callback: function (callback, value, meta) {
            /* Provide file and text for the link dialog */
            if (meta.filetype === 'file') {
            callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
            }

            /* Provide image and alt text for the image dialog */
            if (meta.filetype === 'image') {
            callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
            }

            /* Provide alternative source and posted for the media dialog */
            if (meta.filetype === 'media') {
            callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
            }
        },
        template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
        template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
        height: 600,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_noneditable_class: 'mceNonEditable',
        toolbar_mode: 'sliding',
        contextmenu: 'link image imagetools table',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });

</script>
@endsection
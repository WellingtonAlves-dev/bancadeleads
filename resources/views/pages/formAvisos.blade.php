@extends("templates.form")
@section("title")
Avisos
@endsection
@section("form")
<form method="POST" 
action="{{url("/admin/avisos")}}"
id="formSubmit">
    @csrf
    @include("components.alert")
    <div class="form-group">
        <label>Ativo</label>
        <input type="checkbox" name="ativo" @checked($avisos->ativo ?? false)/>
    </div>
    <div class="form-group">
        <label>Aviso</label>
        <textarea type="text" class="form-control" name="aviso" id="aviso">{{ $avisos->aviso ?? "" }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">
        Salvar
    </button>
    <a href="{{url("/leads")}}" class="btn btn-secondary">Voltar</a>
</form> 
@endsection
@section("script")
<script src="https://cdn.tiny.cloud/1/4z6pziwdxbp71pe3ypfsl52wc4ef94cm6co0pokxy1j9j1da/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">
    tinymce.init({
        selector: '#aviso',
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
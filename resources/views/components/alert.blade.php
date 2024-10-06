@if(Session::has("success_save"))
    <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get("success_save") }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    </div>
@endif
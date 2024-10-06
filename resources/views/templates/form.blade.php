@extends("templates.default")
@section("content")

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 font-weight-bold">@yield("title")</h1>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    @yield("form")
                                </div>
                            </div>
                        </div>
                    </div>

@endsection
@section("script")
@endsection
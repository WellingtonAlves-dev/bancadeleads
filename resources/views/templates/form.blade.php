@extends("templates.default")
@section("content")

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        @yield("title")
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
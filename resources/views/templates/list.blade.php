@extends("templates.default")
@section("content")

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 font-weight-bold">
                            @yield("title")
                        </h1>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header">
                                    <div class="w-100 ml-auto">
                                        @yield("menu")
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        @yield("tabela")
                                    </div>        
                                </div>
                            </div>
                        </div>
                    </div>

@endsection
@extends("templates.default")
@section("content")

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            @yield("title")
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
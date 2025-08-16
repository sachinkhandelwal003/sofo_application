@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">

                    <div class="row flex-between-end">
                        <div class="col-auto align-self-center">
                            <h5 class="mb-0" id="table-example">States :: States List </h5>
                        </div>
                        <div class="col-auto ms-auto">
                            {{-- @if (Helper::userCan(105, 'can_add'))
                                <div class="nav nav-pills nav-pills-falcon">
                                    <button class="btn btn-outline-secondary add"> <i class="fa fa-plus me-1"></i> Add
                                        State</button>
                                </div>
                            @endif --}}
                        </div>
                    </div>
                </div>
                <div class="card-body table-padding">
                    <div class="table-responsive scrollbar">
                        <table id="zero-config"
                            class="table custom-table table-striped dt-table-hover fs--1 mb-0 table-datatable"
                            style="width:100%">
                            <thead class="bg-200 text-900">
                                <tr>
                                    <th>Phone Code</th>
                                    <th>Name</th>
                                  
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

  

   
@endsection


@section('js')
    <script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            var table = $('.table-datatable').DataTable({
                ajax: "{{ route('admin.countries') }}",
                order: [
                    [1, 'asc']
                ],
                columns: [
                    {
                        data: 'phonecode',
                        name: 'phonecode'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    
                ]
            });
           
        });
    </script>
@endsection

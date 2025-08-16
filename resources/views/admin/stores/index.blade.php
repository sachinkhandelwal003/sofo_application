@extends('admin.layouts.app')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0" data-anchor="data-anchor">Stores :: Stores List</h5>
            </div>
        </div>
    </div>
    <div class="card-body table-padding">
        <div class="table-responsive scrollbar">
            <table class="table custom-table table-striped dt-table-hover fs--1 mb-0 table-datatable" style="width:100%">
                <thead class="bg-200 text-900">
                    <tr>
                        <th>Vendor Name</th>
                        <th>Shop Name</th>
                        <th>GST Number</th>
                        <th>PAN Number</th>
                        <th>TAN Number</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th width="180px">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
<script type="text/javascript">
    $(function () {
        var table = $('.table-datatable').DataTable({
            ajax: "{{ route('admin.stores') }}",
            order: [[7, 'desc']], // Order by created_at
            columns: [
                { data: 'app_user_name', name: 'app_user_name' },
                { data: 'shop_name', name: 'shop_name' },
                { data: 'gst_no', name: 'gst_no' },
                { data: 'pan_no', name: 'pan_no' },
                { data: 'tanno', name: 'tanno' },
                { data: 'address', name: 'address' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { 
                    data: 'action', 
                    name: 'action',
                    orderable: false,
                    searchable: false 
                }
            ]
        });
    });
</script>
@endsection
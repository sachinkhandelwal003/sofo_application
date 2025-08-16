@extends('admin.layouts.app')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0" data-anchor="data-anchor">Items for: {{ $vendor->shop_name }}</h5>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('admin.stores') }}" class="btn btn-sm btn-secondary">Back to Stores</a>
            </div>
        </div>
    </div>
    <div class="card-body table-padding">
        <div class="table-responsive scrollbar">
            <table class="table custom-table table-striped dt-table-hover fs--1 mb-0 table-datatable" style="width:100%">
                <thead class="bg-200 text-900">
                    <tr>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Brand</th>
                        <th>Size</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(function () {
        var table = $('.table-datatable').DataTable({
            ajax: window.location.href,
            columns: [
                { data: 'name', name: 'name' },
                { data: 'image', name: 'image' },
                { data: 'price', name: 'price' },
                { data: 'brand', name: 'brand' },
                { data: 'size', name: 'size' },
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
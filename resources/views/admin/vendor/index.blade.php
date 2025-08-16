@extends('admin.layouts.app')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0" data-anchor="data-anchor">Vendor :: Vendor List</h5>
            </div>
        </div>
    </div>
    <div class="card-body table-padding">
        <div class="table-responsive scrollbar">
            <table class="table custom-table table-striped dt-table-hover fs--1 mb-0 table-datatable"
                style="width:100%">
                <thead class="bg-200 text-900">
                    <tr>
                        <th>Shop Name</th>
                        <th>Shop Image</th>
                        <th>GST Number</th>
                        <th>Pan Number</th>
                        <th>Tan Number</th>
                        <th>Address</th>
                        <th>Category</th>
                        <th>Other Category</th>
                        <th>Status</th>
                        <th>Created Date</th>
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
<script>
    $(function () {
        const table = $('.table-datatable').DataTable({
            ajax: "{{ route('admin.vendor') }}",
            order: [[8, 'desc']],
            columns: [
                { data: 'shop_name' },
                { data: 'shop_image' },
                { data: 'gst_no' },
                { data: 'pan_no' },
                { data: 'tanno' },
                { data: 'address' },
                { data: 'categories' },
                { data: 'other_categories' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'created_at' },
            ]
        });

        // ✅ Define the function before using it
        function updateStatus(id, status) {
            $.ajax({
                url: "{{ route('admin.vendor.changeStatus') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status: status
                },
                success: function (res) {
                    if (res.status) {
                        Swal.fire('Updated!', res.message, 'success');
                        table.ajax.reload(null, false); // reload without reset pagination
                    } else {
                        Swal.fire('Error!', 'Status update failed.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Server not responding.', 'error');
                }
            });
        }

        // ✅ Click handler using the function
        $(document).on('click', '.change-status', function () {
            let vendorId = $(this).data('id');
            Swal.fire({
                title: 'Change Status',
                text: 'Do you want to approve or disapprove this vendor?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Approve',
                denyButtonText: 'Disapprove',
                showDenyButton: true,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    updateStatus(vendorId, 1);
                } else if (result.isDenied) {
                    updateStatus(vendorId, 0);
                }
            });
        });
    });
</script>

@endsection

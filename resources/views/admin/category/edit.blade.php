@extends('admin.layouts.app')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0" data-anchor="data-anchor">Edit Category</h5>
            </div>
            <div class="col-auto ms-auto">
                <div class="nav nav-pills nav-pills-falcon flex-grow-1 mt-2" role="tablist">
                    <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left me-1"></i>
                        Go Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form class="row" id="editCategoryForm" method="POST" action="{{ route('admin.categories.update', $category->id) }}">
            @csrf
            
            <div class="col-lg-6 mt-2">
                <label class="form-label" for="name">Name</label>
                <input class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       placeholder="Category Name" 
                       name="name" 
                       type="text"
                       value="{{ old('name', $category->name) }}" />
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-lg-6 mt-2">
                <label class="form-label" for="image">Image</label>
                <div class="img-group mb-2">
                    <img class="" src="{{ asset('storage/' . $category['image']) }}" alt="">
                </div>
                <input class="form-control" id="image" name="image" type="file" value="" />
                @error('image')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-lg-6 mt-2">
                <label class="form-label" for="status">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" id="status">
                    <option value="1" @selected(old('status', $category->status) == 1)>Active</option>
                    <option value="0" @selected(old('status', $category->status) == 0)>Inactive</option>
                </select>
                @error('status')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-lg-12 mt-3 d-flex justify-content-start">
                <button class="btn btn-primary" type="submit">Update Category</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $("#editCategoryForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 200
                },
                status: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Please enter category name",
                    minlength: "Category name must be at least 2 characters",
                    maxlength: "Category name cannot exceed 200 characters"
                },
                status: {
                    required: "Please select status"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endsection
@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/summernote/summernote.min.css') }}">
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0">Category :: Category Add</h5>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Go Back
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form class="row" id="add" method="POST" action="{{ route('admin.categories.add') }}" enctype='multipart/form-data'>
            @csrf
            <div class="col-lg-6 mt-2">
                <label class="form-label" for="name">Name <span class="required">*</span></label>
                <input class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Category name" name="name" type="text" value="{{ old('name') }}">
                @error('name')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="col-lg-6 mt-2">
                <label class="form-label" for="image">Image</label>
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
                    <option value="1" @selected(old('status', 1) == 1)> Active </option>
                    <option value="0" @selected(old('status', 1) == 0)> Inactive </option>
                </select>
                @error('status')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="col-lg-12 mt-3">
                <button class="btn btn-secondary" type="submit">Add</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('assets/plugins/summernote/summernote.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $("#add").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 200
                }
            },
            messages: {
                name: {
                    required: "Please enter category name",
                    minlength: "Category name must be at least 2 characters",
                    maxlength: "Category name must not exceed 200 characters"
                }
            }
        });
    });
</script>
@endsection

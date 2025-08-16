@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/summernote/summernote.min.css') }}">
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-end">
            <div class="col-auto align-self-center">
                <h5 class="mb-0" data-anchor="data-anchor">Store Item :: Store Item Add </h5>
            </div>
            <div class="col-auto ms-auto">
                <div class="nav nav-pills nav-pills-falcon flex-grow-1" role="tablist">
                    <a href="{{ route('admin.store-iteam')  }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left me-1"></i>
                        Go Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
    <form class="row" id="add" method="POST" action="{{ route('admin.store-iteam.add') }}" enctype="multipart/form-data">
    @csrf

    <div class="col-lg-6 mt-2">
        <label class="form-label" for="store_id">Store <span class="required">*</span></label>
        <select class="form-select" name="store_id" id="store_id" required>
            <option value="">Select Store</option>
            @foreach($store as $item)
                <option value="{{ $item->id }}" @selected(old('store_id') == $item->id)>{{ $item->title }}</option>
            @endforeach
        </select>
        @error('store_id')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-lg-6 mt-2">
        <label class="form-label" for="name">Name <span class="required">*</span></label>
        <input class="form-control" id="name" name="name" type="text" value="{{ old('name') }}" required />
        @error('name')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-lg-6 mt-2">
        <label class="form-label" for="price">Price <span class="required">*</span></label>
        <input class="form-control" id="price" name="price" type="number" step="0.01" value="{{ old('price') }}" required />
        @error('price')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
    
     <div class="col-lg-6 mt-2">
                <label class="form-label" for="size">Size</label>
                    <input class="form-control" id="size" name="size" type="text" value="{{ old('size') }}" required />
                @error('size')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
    </div>
     <div class="col-lg-6 mt-2">
                <label class="form-label" for="brand">Brand</label>
                    <input class="form-control" id="brand" name="brand" type="text" value="{{ old('brand') }}" required />
                @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
    </div>
    <div class="col-lg-6 mt-2">
        <label class="form-label" for="image">Image</label>
        <input class="form-control" id="image" name="image" type="file" />
        @error('image')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-lg-6 mt-2">
        <label class="form-label" for="status">Status</label>
        <select name="status" class="form-select" id="status">
            <option value="1" @selected(old('status', 1) == 1)>Active</option>
            <option value="0" @selected(old('status', 1) == 0)>Inactive</option>
        </select>
        @error('status')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
<div class="col-lg-12 mt-2">
                <label class="form-label" for="description">About</label>
                <textarea class="form-control" id="description" name="about"></textarea>
                @error('about')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
    </div>
    <div class="col-lg-12 mt-3 d-flex justify-content-start">
        <button class="btn btn-secondary submitbtn" type="submit">Add</button>
    </div>
</form>

    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('assets/plugins/summernote/summernote.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#description').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['codeview', 'help']],
            ]
        });
        let buttons = $('.note-editor button[data-toggle="dropdown"]');
        buttons.each((key, value) => {
            $(value).on('click', function (e) {
                $(this).attr('data-bs-toggle', 'dropdown')
            })
        })
    })
    $("#add").validate({
        ignore: ".ql-container *",
        rules: {
            title: {
                required: true,
                minlength: 2,
                maxlength: 100
            },
            image: {
                extension: "jpg|jpeg|png"
            }
        },
        messages: {
            title: {
                required: "Please enter title",
            },
            image: {
                extension: "Supported Format Only : jpg, jpeg, png"
            }
        },
    });
</script>
@endsection
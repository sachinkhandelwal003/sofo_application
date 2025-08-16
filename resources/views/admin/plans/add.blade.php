@extends('admin.layouts.app')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.5.2/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-end">
                <div class="col-auto align-self-center">
                    <h5 class="mb-0" data-anchor="data-anchor">Plans :: Plan Add </h5>
                </div>
                <div class="col-auto ms-auto">
                    <div class="nav nav-pills nav-pills-falcon flex-grow-1" role="tablist">
                        <a href="{{ route('admin.plans') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left me-1"></i>
                            Go Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="row" id="add" method="POST" action="{{ route('admin.plans.add') }}"
                enctype='multipart/form-data'>
                @csrf

                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="sub_name">Sub Name <span class="required">*</span></label>
                    <input class="form-control" id="sub_name" placeholder="Enter Sub Name" name="sub_name" type="text"
                        value="{{ old('sub_name') }}" />
                    @error('sub_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="name">Name <span class="required">*</span></label>
                    <input class="form-control" id="name" placeholder="Enter Name" name="name" type="text"
                        value="{{ old('name') }}" />
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="price">Price <span class="required">*</span></label>
                    <input class="form-control" id="price" placeholder="Enter Price" name="price" type="number"
                        value="{{ old('price') }}" />
                    @error('price ')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="countries">Countries <span class="required">*</span></label>
                    <select name="countries[]" class="form-select" id="countries" multiple>
                        <option value=""> Select Countries </option>
                        @if (!empty($countries))
                            @foreach ($countries as $country)
                                <option value="{{ $country['id'] }}" @selected(old('countries') == $country['id'])> {{ $country['name'] }}
                                </option>
                            @endforeach
                        @endif

                    </select>
                    @error('countries')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="status">Status <span class="required">*</span></label>
                    <select name="status" class="form-select" id="status">
                        <option value="1" @selected(old('status', 1) == 1)> Active </option>
                        <option value="0" @selected(old('status', 1) == 0)> Inactive </option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="icon">Icon <span class="required">*</span></label>
                    <input class="form-control" id="icon" name="icon" type="file" value="" />
                    @error('icon')
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#countries').select2({
                placeholder: 'Select Countries',
                allowClear: true,
            });
        });

        $("#add").validate({

            rules: {
                sub_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 20
                },
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 100
                },
                price: {
                    required: true,
                    number: true
                },
                'countries[]': {
                    required: true,
                },
                status: {
                    required: true,
                },
                icon: {
                    required: true,
                    extension: "jpg|jpeg|png"
                }
            },
            messages: {
                sub_name: {
                    required: "Please enter sub name",
                },
                name: {
                    required: "Please enter name",
                },
                price: {
                    required: "Please enter price",
                },
                'countries[]': {
                    required: "Please select country",
                },
                status: {
                    required: "Please select status",
                },
                icon: {
                    required: "Please select icon",
                    extension: "Please select image"
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "countries[]") {
                error.insertAfter(element.parent().find(".select2-container"));
                }
                else {
                error.insertAfter(element);
                }
            }
        });
    </script>
@endsection

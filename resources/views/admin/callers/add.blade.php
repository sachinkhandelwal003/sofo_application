@extends('admin.layouts.app')

@section('content')
    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-end">
                <div class="col-auto align-self-center">
                    <h5 class="mb-0" data-anchor="data-anchor">Callers :: Caller Add </h5>
                </div>
                <div class="col-auto ms-auto">
                    <div class="nav nav-pills nav-pills-falcon flex-grow-1" role="tablist">
                        <a href="{{ route('admin.callers') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left me-1"></i>
                            Go Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="row" id="add" method="POST" action="{{ route('admin.callers.add') }}"
                enctype='multipart/form-data'>
                @csrf

                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="userId">UserId <span class="required">*</span></label>
                    <input class="form-control" id="userId" placeholder="Enter UserId" name="userId" type="text"
                        value="{{ old('userId') }}" />
                    @error('userId')
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
                    <label class="form-label" for="email">Email <span class="required">*</span></label>
                    <input class="form-control" id="email" placeholder="Enter Email" name="email" type="email"
                        value="{{ old('email') }}" />
                    @error('email ')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="mobile">Mobile <span class="required">*</span></label>
                    <input class="form-control" id="mobile" placeholder="Enter Mobile" name="mobile" type="tel"
                        value="{{ old('mobile') }}" />
                    @error('mobile')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="password">Password <span class="required">*</span></label>
                    <input class="form-control" id="password" placeholder="Enter Password" name="password" type="password"
                        value="{{ old('password') }}" />
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="password_confirmation">Confirm Password <span
                            class="required">*</span></label>
                    <input class="form-control" id="password_confirmation" placeholder="Enter Confirm Password"
                        name="password_confirmation" type="password" value="{{ old('password_confirmation') }}" />
                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="status">Status</label>
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
                    <label class="form-label" for="image">Image</label>
                    <input class="form-control" id="image" name="image" type="file" value="" />
                    @error('image')
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
    <script type="text/javascript">
        $("#add").validate({

            rules: {
                userId: {
                    required: true,
                    minlength: 2,
                    maxlength: 20
                },
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 100
                },
                email: {
                    required: true,
                    email: true
                },
                mobile: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 8,
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                },
                status: {
                    required: true,
                },
                image: {
                    extension: "jpg|jpeg|png"
                }
            },
            messages: {
              userId: {
                    required: "Please enter UserId",
                },
                name: {
                    required: "Please enter Name",
                },
                email: {
                    required: "Please enter Email",
                },
                mobile: {
                    required: "Please enter Mobile",
                },
                password: {
                    required: "Please enter Password",
                },
                password_confirmation: {
                    required: "Please enter Confirm Password",
                },
                status: {
                    required: "Please enter Status",
                },
                image: {
                    extension: "Allow Types : .jpg, .png, .jpeg"
                },
            },
        });
    </script>
@endsection

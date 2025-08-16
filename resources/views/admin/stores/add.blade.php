@extends('admin.layouts.app')

@section('content')
    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-end">
                <div class="col-auto align-self-center">
                    <h5 class="mb-0">Add New Store</h5>
                </div>
                <div class="col-auto ms-auto">
                    <div class="nav nav-pills nav-pills-falcon flex-grow-1 mt-2" role="tablist">
                        <a href="{{ route('admin.stores') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left me-1"></i>
                            Go Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="row" id="addStoreForm" method="POST" action="{{ route('admin.stores.add') }}"
                enctype='multipart/form-data'>
                @csrf

                <!-- Title -->
                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                    <input class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Store Title"
                        name="title" type="text" value="{{ old('title') }}" />
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Category -->
                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="category_id">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror"
                        id="category_id">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Location -->
                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="location">Location <span class="text-danger">*</span></label>
                    <input class="form-control @error('location') is-invalid @enderror" id="location"
                        placeholder="Store Location" name="location" type="text" value="{{ old('location') }}" />
                    @error('location')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Contact -->
                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="contact">Contact <span class="text-danger">*</span></label>
                    <input class="form-control @error('contact') is-invalid @enderror" id="contact"
                        placeholder="Contact Number" name="contact" type="text" value="{{ old('contact') }}" />
                    @error('contact')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                    <input class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email Address"
                        name="email" type="email" value="{{ old('email') }}" />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Website -->
                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="website">Website</label>
                    <input class="form-control @error('website') is-invalid @enderror" id="website"
                        placeholder="Website URL" name="website" type="url" value="{{ old('website') }}" />
                    @error('website')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- About -->
                <div class="col-lg-12 mt-2">
                    <label class="form-label" for="about">About <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('about') is-invalid @enderror" id="about" name="about"
                        rows="3">{{ old('about') }}</textarea>
                    @error('about')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Delivery -->
                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="delivery">Delivery Available <span class="text-danger">*</span></label>
                    <select name="delivery" class="form-select @error('delivery') is-invalid @enderror" id="delivery">
                        <option value="1" @selected(old('delivery', 1) == 1)>Yes</option>
                        <option value="0" @selected(old('delivery', 1) == 0)>No</option>
                    </select>
                    @error('delivery')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Rating -->
                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="rating">Rating (0-5)</label>
                    <input class="form-control @error('rating') is-invalid @enderror" id="rating" placeholder="Rating"
                        name="rating" type="number" min="0" max="5" step="0.1" value="{{ old('rating') }}" />
                    @error('rating')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Image -->
                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="image">Image <span class="text-danger">*</span></label>
                    <input class="form-control @error('image') is-invalid @enderror" id="image" name="image" type="file" />
                    @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-lg-6 mt-2">
                    <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" id="status">
                        <option value="1" @selected(old('status', 1) == 1)>Active</option>
                        <option value="0" @selected(old('status', 1) == 0)>Inactive</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Description -->
                <div class="col-lg-12 mt-2">
                    <label class="form-label" for="description">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                        name="description" rows="5">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-lg-12 mt-3 d-flex justify-content-start">
                    <button class="btn btn-primary" type="submit">Add Store</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $("#addStoreForm").validate({
                rules: {
                    title: { required: true, maxlength: 200 },
                    category_id: { required: true },
                    location: { required: true, maxlength: 255 },
                    contact: { required: true, maxlength: 20 },
                    email: { required: true, email: true, maxlength: 255 },
                    website: { url: true, maxlength: 255 },
                    about: { required: true, maxlength: 5000 },
                    delivery: { required: true },
                    rating: { number: true, min: 0, max: 5 },
                    status: { required: true },
                    description: { required: true, maxlength: 10000 },
                    image: { required: true }
                },
                messages: {
                    title: { required: "Please enter store title" },
                    category_id: { required: "Please select a category" },
                    location: { required: "Please enter store location" },
                    contact: { required: "Please enter contact number" },
                    email: { required: "Please enter valid email" },
                    about: { required: "Please enter about information" },
                    delivery: { required: "Please select delivery option" },
                    rating: { number: "Please enter valid rating (0-5)" },
                    description: { required: "Please enter store description" },
                    image: { required: "Please upload store image" }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
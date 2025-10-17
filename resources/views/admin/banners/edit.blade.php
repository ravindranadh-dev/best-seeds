@extends('admin.layouts.main')

@section('page_title', 'Edit Banner')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Edit Banner</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Banners</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Update Banner</h4>
                    @include('flash_msg')

                    <form class="forms-sample" method="POST" action="{{ route('banners.update', $banner->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $banner->title) }}">
                                @error('title')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
    <label class="form-label">Upload Banner Image</label>
    <input type="file" name="banner_image" class="filepond" data-max-file-size="5MB" accept="image/*">
    @error('banner_image')
        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
    @enderror

    @if (!empty($banner->image))
        <div class="mt-2">
            <strong>Current Image:</strong><br>
            <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner Image" style="max-height: 120px;">
        </div>
    @endif
</div>
                        </div>

                        <div class="row mb-3">


<div class="col-md-6 mb-3">
    <label class="form-label">Screen</label>
    <select name="screen" class="form-control @error('screen') is-invalid @enderror">
        <option value="">Select Screen</option>
        <option value="home" {{ old('screen', $selectedScreen ?? '') == 'home' ? 'selected' : '' }}>Home</option>
        <option value="login" {{ old('screen', $selectedScreen ?? '') == 'login' ? 'selected' : '' }}>Login</option>
        <option value="booking" {{ old('screen', $selectedScreen ?? '') == 'booking' ? 'selected' : '' }}>Booking</option>
        <option value="cart" {{ old('screen', $selectedScreen ?? '') == 'cart' ? 'selected' : '' }}>Cart</option>
        <option value="stock" {{ old('screen', $selectedScreen ?? '') == 'stock' ? 'selected' : '' }}>Stock</option>
    </select>

    @error('screen')
        <span class="invalid-feedback d-block">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
  <div class="col-md-6 mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-control @error('status') is-invalid @enderror">
            <option value="1" {{ old('status', $banner->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $banner->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <span class="invalid-feedback d-block">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>



                        </div>



                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary me-2">Update</button>
                            <a href="{{ route('banners.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<!-- Select2 CSS -->



<link href="{{ asset('admin_assets/ravindra/css/select2.min.css') }}" rel="stylesheet" />

<script src="{{ asset('admin_assets/ravindra/js/jquery-3.6.0.min.js') }}"></script>

<script src="{{ asset('admin_assets/ravindra/js/select2.min.js') }}"></script>

{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

<!-- jQuery -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<!-- Select2 JS -->
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select categories",
        allowClear: true
    });
});
</script>
@endpush

@extends('admin.layouts.main')

@section('page_title', 'Create Booking')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Create Booking</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">Bookings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Add New Booking</h4>
                    @include('flash_msg')

                    <form method="POST" action="{{ route('bookings.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            {{-- <div class="col-md-6 mb-3">
                                <label class="form-label">Vendor ID</label>
                                <input type="number" name="vendor_id" class="form-control" value="{{ old('vendor_id') }}" required>
                                @error('vendor_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div> --}}
                                <div class="col-md-6">
                                <label class="form-label">Vendor</label>
                                <select name="vendor_id" class="form-control">
                                    <option value="">Select Hatcheries</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer ID</label>
                                <input type="text" name="customer_id" class="form-control" value="{{ old('customer_id') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer Name</label>
                                <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer Mobile</label>
                                <input type="text" name="customer_mobile" class="form-control" value="{{ old('customer_mobile') }}" required>
                            </div>
             <div class="col-md-6">
                                <label class="form-label">Delivery Location</label>
                                <select name="delivery_location" class="form-control">
                                    <option value="">Select Hatcheries</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->location_name }}" {{ old('delivery_location') == $location->location_name ? 'selected' : '' }}>
                                            {{ $location->location_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('delivery_location')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- <div class="col-md-6 mb-3">
                                <label class="form-label">Delivery Location</label>
                                <input type="text" name="delivery_location" class="form-control" value="{{ old('delivery_location') }}">
                            </div> --}}

                            {{-- <div class="col-md-6 mb-3">
                                <label class="form-label">Hatchery Name</label>
                                <input type="text" name="hatchery_name" class="form-control" value="{{ old('hatchery_name') }}" required>
                            </div> --}}
                                    <div class="col-md-6">
                                <label class="form-label">Hatchery Name</label>
                                <select name="hatchery_name" class="form-control">
                                    <option value="">Select Hatcheries</option>
                                    @foreach($data as $hatchery)
                                        <option value="{{ $hatchery->hatchery_name }}" {{ old('hatchery_name') == $hatchery->hatchery_name ? 'selected' : '' }}>
                                            {{ $hatchery->hatchery_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hatchery_name')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit</label>
                                <input type="text" name="unit" class="form-control" value="{{ old('unit') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. of Pieces</label>
                                <input type="number" name="no_of_pieces" class="form-control" value="{{ old('no_of_pieces') }}">
                            </div>
                                         <div class="col-md-6">
                                <label class="form-label">Dropping Location</label>
                                <select name="dropping_location" class="form-control">
                                    <option value="">Select Hatcheries</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->location_name }}" {{ old('dropping_location') == $location->location_name ? 'selected' : '' }}>
                                            {{ $location->location_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dropping_location')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- <div class="col-md-6 mb-3">
                                <label class="form-label">Dropping Location</label>
                                <input type="text" name="dropping_location" class="form-control" value="{{ old('dropping_location') }}">
                            </div> --}}

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Packing Date</label>
                                <input type="date" name="packing_date" class="form-control" value="{{ old('packing_date') }}">
                            </div>

                            {{-- <div class="col-md-6 mb-3">
                                <label class="form-label">Categories</label>
                                <textarea name="categories" class="form-control" rows="2">{{ old('categories') }}</textarea>
                            </div> --}}
                            <div class="col-md-6 mb-3">
    <label class="form-label">Categories</label>
    <select name="categories[]" class="form-control select2" multiple>
        @foreach($allcategories as $category)
            <option value="{{ $category->id }}"
                {{ in_array($category->id, old('categories', $booking->categories ?? [])) ? 'selected' : '' }}>
                {{ $category->category_name }}
            </option>
        @endforeach
    </select>
</div>



                            <div class="col-md-6 mb-3">
                                <label class="form-label">Count</label>
                                <input type="number" name="count" class="form-control" value="{{ old('count', 0) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Driver Name</label>
                                <input type="text" name="driver_name" class="form-control" value="{{ old('driver_name') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Driver Mobile</label>
                                <input type="text" name="driver_mobile" class="form-control" value="{{ old('driver_mobile') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Vehicle Number</label>
                                <input type="text" name="vehicle_number" class="form-control" value="{{ old('vehicle_number') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Vehicle Started Date</label>
                                <input type="date" name="vehicle_started_date" class="form-control" value="{{ old('vehicle_started_date') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Upload Vehicle Images</label>
                                <input type="file" name="vehicle_images[]" class="filepond" multiple data-max-file-size="10MB" accept="image/*">
                                @error('vehicle_images') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary me-2">Create</button>
                            <a href="{{ route('bookings.index') }}" class="btn btn-light">Cancel</a>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select categories",
        allowClear: true
    });
});
</script>
@endpush

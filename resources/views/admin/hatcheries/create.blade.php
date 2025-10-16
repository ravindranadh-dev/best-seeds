@extends('admin.layouts.main')

@section('page_title', 'Register Hatchery')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Register Hatchery</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Hatchery</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Create Hatchery</h4>
                    @include('flash_msg')

                    <form method="POST" action="{{ route('hatcheries.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Hatchery Name</label>
                                <input type="text" name="hatchery_name" class="form-control" value="{{ old('hatchery_name') }}">
                                @error('hatchery_name')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-md-6">
    <label class="form-label">Select Vendor</label>
    <select name="vendor_id" class="form-control">
        <option value="">Choose Vendor</option>
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



                            {{-- <div class="col-md-6">
                                <label class="form-label">Brand</label>
                                <input type="text" name="brand" class="form-control" value="{{ old('brand') }}">
                                @error('brand')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div> --}}
                        </div>

                        {{-- Multi-select Categories and Locations --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Categories</label>
                                <select name="category_id[]" class="form-control select2" multiple="multiple" data-placeholder="Select categories">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ (collect(old('category_id'))->contains($cat->id)) ? 'selected' : '' }}>
                                            {{ $cat->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Locations</label>
                                <select name="location_id[]" class="form-control select2" multiple="multiple" data-placeholder="Select locations">
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" {{ (collect(old('location_id'))->contains($loc->id)) ? 'selected' : '' }}>
                                            {{ $loc->location_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_id')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Opening Time</label>
                                <input type="time" name="opening_time" class="form-control" value="{{ old('opening_time') }}">
                                @error('opening_time')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Closing Time</label>
                                <input type="time" name="closing_time" class="form-control" value="{{ old('closing_time') }}">
                                @error('closing_time')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="lat" class="form-control" value="{{ old('lat') }}">
                                @error('lat')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="lng" class="form-control" value="{{ old('lng') }}">
                                @error('lng')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div> --}}

                        <div class="mb-3">
                            <label class="form-label">Upload Hatchery Images (PNG, JPG)</label>
                            <input type="file" name="image[]" class="form-control" multiple accept="image/png,image/jpeg">
                            @error('image')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary me-2">Register</button>
                            <a href="{{ route('hatcheries.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--multiple {
        min-height: 40px;
        border-radius: 6px;
        border: 1px solid #ced4da;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: function(){
                $(this).data('placeholder');
            },
            width: '100%'
        });
    });
</script>
@endpush

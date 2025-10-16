@extends('admin.layouts.main')

@section('content')

{{-- Move this to the top of your file --}}
@section('page_title', 'Vendor')

    <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
                Vendor
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Vendor</li>
                </ol>
            </nav>
          </div>
          <div class="row">

<div class="col-12 grid-margin stretch-card">
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Edit Vendor</h4>
 @include('flash_msg')
        <form class="forms-sample" method="POST" action="{{ route('vendors.update', $data->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
<div class="row mb-3">
    <div class="col-md-12">
        <label class="form-label">Profile Image</label>
        <input type="file"
               class="filepond"
               name="profile_image"
               id="profile-image-upload"
               accept="image/*"
               data-max-file-size="2MB">

        @if(!empty($data->profile_image))
    <div class="mt-2 position-relative d-inline-block">
        <img src="{{ asset('uploads/vendor/profile/' . $data->profile_image) }}"
             alt="Profile Image"
             width="120"
             class="rounded mt-2">
        <input type="hidden" name="profile_image" value="{{ $data->profile_image }}">

        <!-- Delete button with confirmation -->
        <button type="button"
                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle"
                onclick="confirmDelete('{{ $data->profile_image }}', '{{ $data->id }}', 'Vendor')"
                title="Remove Image">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif
        @error('profile_image')
            <span class="invalid-feedback d-block">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $data->name) }}">
                    @error('name')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $data->mobile) }}">
                    @error('mobile')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Alternate Mobile</label>
                    <input type="text" name="alternate_mobile" class="form-control" value="{{ old('alternate_mobile', $data->alternate_mobile) }}">
                    @error('alternate_mobile')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Pincode</label>
                    <input type="text" name="pincode" class="form-control" value="{{ old('pincode', $data->pincode) }}">
                    @error('pincode')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address', $data->address) }}</textarea>
                    @error('address')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>




            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary me-2">Update</button>
                <a href="{{ route('vendors.index') }}" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>
</div>

</div>










          </div>
        </div>
@endsection
@push('scripts')
<script>
function confirmDelete(imageName, modelId, modelType) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send AJAX request to delete the image
            fetch('{{ route("admin.image.delete") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    image_name: imageName,
                    model_id: modelId,
                    model_type: modelType
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the image preview
                    const imageContainer = document.querySelector('.position-relative');
                    if (imageContainer) {
                        imageContainer.remove();
                    }
                    // Show success message
                    Swal.fire('Deleted!', 'The image has been deleted.', 'success');
                } else {
                    Swal.fire('Error!', data.message || 'Failed to delete image.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'An error occurred while deleting the image.', 'error');
            });
        }
    });
}
</script>
@endpush


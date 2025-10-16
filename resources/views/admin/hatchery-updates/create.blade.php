@extends('admin.layouts.main')

@section('page_title', 'Create Hatchery Update')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Create Hatchery Update</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hatchery-updates.index') }}">Hatchery Updates</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Add New Update</h4>
                    @include('flash_msg')

               <form method="POST" action="{{ route('hatchery-updates.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

  <div class="col-md-6 mb-3">
    <label class="form-label">Hatchery</label>
    <select name="hatchery_id" id="hatchery_id" class="form-control">
        <option value="">Select Hatchery</option>
        @foreach ($hatcheries as $hatchery)
            <option value="{{ $hatchery->id }}" {{ old('hatchery_id') == $hatchery->id ? 'selected' : '' }}>
                {{ $hatchery->hatchery_name }}
            </option>
        @endforeach
    </select>
    @error('hatchery_id')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>


        <div class="col-md-6 mb-3">
            <label class="form-label">Media Type</label>
            <select name="media_type" id="media_type" class="form-control">
                <option value="image" {{ old('media_type') === 'image' ? 'selected' : '' }}>Image</option>
                <option value="video" {{ old('media_type') === 'video' ? 'selected' : '' }}>Video</option>
            </select>
        </div>

        {{-- File upload (replaces text field) --}}
        <div class="col-md-6 mb-3">
            <label class="form-label">Upload Media</label>
            <input type="file"
                   name="media_path"
                   id="media_path"
                   class="filepond"
                   data-max-file-size="50MB"
                   accept="{{ old('media_type', 'image') === 'video' ? 'video/*' : 'image/*' }}">
            @error('media_path') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Hashtags (comma separated)</label>
            <input type="text" name="hashtags" class="form-control" value="{{ old('hashtags') }}">
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control sun-editor" rows="4">{{ old('description') }}</textarea>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Status</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>

    <div class="text-end mt-4">
        <button type="submit" class="btn btn-primary me-2">Create</button>
        <a href="{{ route('hatchery-updates.index') }}" class="btn btn-light">Cancel</a>
    </div>
</form>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection

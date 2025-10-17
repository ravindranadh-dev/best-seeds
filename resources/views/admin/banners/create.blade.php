@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Banner</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('danger'))
                        <div class="alert alert-danger">{{ session('danger') }}</div>
                    @endif

                    <form method="POST" action="{{ route('banners.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <select class="form-select @error('image') is-invalid @enderror" name="image" id="image">
                                <option value="">Select Image</option>
                                <option value="banner_home.jpg">Home Banner</option>
                                <option value="banner_login.jpg">Login Banner</option>
                                <option value="banner_offer.jpg">Offer Banner</option>
                            </select>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="redirect_url" class="form-label">Redirect URL</label>
                            <input type="url" class="form-control @error('redirect_url') is-invalid @enderror"
                                   id="redirect_url" name="redirect_url" value="{{ old('redirect_url') }}">
                            @error('redirect_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="screen" class="form-label">Screen</label>
                            <select class="form-select @error('screen') is-invalid @enderror" name="screen" id="screen">
                                <option value="">Select Screen</option>
                                <option value="home">Home</option>
                                <option value="login">Login</option>
                                <option value="dashboard">Dashboard</option>
                                <option value="profile">Profile</option>
                            </select>
                            @error('screen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Create Banner</button>
                            <a href="{{ route('banners.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

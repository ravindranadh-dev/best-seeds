@extends('admin.layouts.main')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title d-flex align-items-center">
            <i class="fas fa-bullhorn mr-2"></i> Hatchery Posts
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}"><i class="fas fa-home mr-1"></i> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Hatchery Posts</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">All Posts</h4>
                <a href="{{ route('hatchery-updates.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-1"></i> Add Post
                </a>
            </div>

            <div class="table-responsive">
                <table id="data-table" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Media</th>
                            <th>Description</th>
                            <th>Hashtags</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                        <tr>
                            <td class="font-weight-bold text-primary">#{{ $post->id }}</td>
                            <td>{{ $post->title }}</td>
                            <td>
                                @if($post->media_path)
                                    @if($post->media_type === 'video')
                                        <video width="80" controls>
                                            <source src="{{ asset($post->media_path) }}" type="video/mp4">
                                        </video>
                                    @else
                                        <img src="{{ asset($post->media_path) }}" class="img-thumbnail" style="width: 80px;">
                                    @endif
                                @else
                                    <span class="badge badge-secondary">No Media</span>
                                @endif
                            </td>
                            <td>{!! Str::limit($post->description, 60) !!}</td>
                            <td>
                                @if($post->hashtags)
                                    @foreach(explode(',', $post->hashtags) as $tag)
                                        <span class="badge badge-info">#{{ trim($tag) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $post->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $post->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('hatchery-updates.edit', $post->id) }}"
                                       class="btn btn-sm btn-primary btn-action ml-1"
                                       title="Edit Post">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('hatchery-updates.destroy', $post->id) }}"
                                          method="POST"
                                          class="d-inline delete-form ml-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger btn-action delete-vendor-btn"
                                                title="Delete Post">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#data-table').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            search: "",
            searchPlaceholder: "Search posts...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries found",
            infoFiltered: "(filtered from _MAX_ total entries)",
            emptyTable: "No posts found",
            paginate: {
                first: "<<",
                last: ">>",
                next: ">",
                previous: "<"
            }
        },
        columnDefs: [
            { targets: -1, orderable: false, className: 'text-center' },
            { targets: 2, orderable: false }
        ],
        initComplete: function() {
            $('.dataTables_filter input').addClass('form-control form-control-sm');
        }
    });

    @if(session('success'))
        showToast('success', "{{ addslashes(session('success')) }}");
    @endif
    @if(session('error') || session('danger'))
        showToast('error', "{{ addslashes(session('error') ?? session('danger')) }}");
    @endif

    $(document).on('click', '.delete-vendor-btn', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endpush

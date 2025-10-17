@extends('admin.layouts.main')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title d-flex align-items-center">
            <i class="fas fa-boxes mr-2"></i> Broad Stock
        </h3>
        @include('flash_msg')
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}"><i class="fas fa-home mr-1"></i> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Broad Stock</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">Broad Stock List</h4>
                <a href="{{ route('broad-stocks.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-1"></i> Add Broad Stock
                </a>
            </div>

            <div class="table-responsive">
                <table id="data-table" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Hatchery Name</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Count</th>
                            <th>Available Date</th>
                            <th>Packing Date</th>
                            <th>Seed Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($broadStocks as $item)
                        <tr>
                            <td class="font-weight-bold text-primary">#{{ $item->id }}</td>

                            <td>{{ $item->hatchery->hatchery_name }}</td>
                            <td>{{ $item->category->category_name ?? 'N/A' }}</td>
                            <td>{{ $item->location->name ?? 'N/A' }}</td>
                            <td>{{ $item->count }}</td>
                            <td>{{ $item->available_date->format('d M Y') }}</td>
                            <td>{{ $item->packing_date->format('d M Y') }}</td>
                           <td>
    @if(!empty($item->seed_image_path) && file_exists(public_path($item->seed_image_path)))
        <img src="{{ asset($item->seed_image_path) }}"
             alt="Seed Image"
             class="img-sm rounded"
             style="width: 40px; height: 40px; object-fit: cover;">
    @else
        <span class="badge bg-secondary">No Image</span>
    @endif
</td>

                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('broad-stocks.edit', $item->id) }}"
                                       class="btn btn-sm btn-primary btn-action ml-1"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('broad-stocks.destroy', $item->id) }}"
                                          method="POST"
                                          class="d-inline delete-form ml-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger btn-action delete-vendor-btn"
                                                title="Delete">
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
<link rel="stylesheet" href="{{asset('admin_assets/ravindra/js/sweetalert.js')}}">
<link rel="stylesheet" href="{{asset('admin_assets/ravindra/js/dataTables.min.js')}}">
<link rel="stylesheet" href="{{asset('admin_assets/ravindra/js/bootstrap4.min.js')}}">

{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script> --}}

<script>
$(document).ready(function() {
    const table = $('#data-table').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            search: "",
            searchPlaceholder: "Search stocks...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries found",
            infoFiltered: "(filtered from _MAX_ total entries)",
            emptyTable: "No stocks found",
            paginate: {
                first: "<<",
                last: ">>",
                next: ">",
                previous: "<"
            }
        },
        columnDefs: [
            {
                targets: -1,
                orderable: false,
                className: 'text-center'
            }
        ],
        initComplete: function() {
            $('.dataTables_filter input').addClass('form-control form-control-sm');
        }
    });

    // Toasts
    function showToast(icon, message) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: icon,
            title: message,
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        });
    }

    @if(session('success'))
        showToast('success', "{{ addslashes(session('success')) }}");
    @endif

    @if(session('error') || session('danger'))
        showToast('error', "{{ addslashes(session('error') ?? session('danger')) }}");
    @endif

    // Delete confirmation
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
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<style>
    .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.5em;
        display: inline-block;
        width: auto;
    }
</style>
@endpush

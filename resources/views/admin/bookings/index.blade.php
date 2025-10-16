@extends('admin.layouts.main')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title d-flex align-items-center">
            <i class="fas fa-truck mr-2"></i>Bookings
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}"><i class="fas fa-home mr-1"></i> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bookings</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">All Bookings</h4>
                <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-1"></i> Add Booking
                </a>
            </div>

            <div class="table-responsive">
                <table id="data-table" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Hatchery</th>
                            <th>Driver</th>
                            <th>Vehicle</th>
                            <th>Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td class="font-weight-bold text-primary">#{{ $booking->id }}</td>
                            <td>
                                <div class="font-weight-medium">{{ $booking->customer_name }}</div>
                                <small class="text-muted">{{ $booking->customer_mobile }}</small>
                            </td>
                            <td>
                                <div class="font-weight-medium">{{ $booking->hatchery_name }}</div>
                                <small class="text-muted">{{ $booking->delivery_location }}</small>
                            </td>
                            <td>
                                <div class="font-weight-medium">{{ $booking->driver_name }}</div>
                                <small class="text-muted">{{ $booking->driver_mobile }}</small>
                            </td>
                            <td>
                                <div class="font-weight-medium">{{ $booking->vehicle_number }}</div>
                                <small class="text-muted">{{ $booking->vehicle_started_date }}</small>
                            </td>
                            <td>{{ $booking->count ?? '0' }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('bookings.edit', $booking->id) }}"
                                       class="btn btn-sm btn-primary btn-action ml-1"
                                       title="Edit Booking">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('bookings.destroy', $booking->id) }}"
                                          method="POST"
                                          class="d-inline delete-form ml-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger btn-action delete-vendor-btn"
                                                title="Delete Booking">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    const table = $('#data-table').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            search: "",
            searchPlaceholder: "Search bookings...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries found",
            infoFiltered: "(filtered from _MAX_ total entries)",
            emptyTable: "No bookings found",
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

    // SweetAlert delete confirmation
    $(document).on('click', '.delete-vendor-btn', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');

        Swal.fire({
            title: 'Are you sure?',
            text: "This booking will be permanently deleted.",
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

    // Toast notifications
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-right',
            icon: 'success',
            title: "{{ addslashes(session('success')) }}",
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true
        });
    @endif

    @if(session('error') || session('danger'))
        Swal.fire({
            toast: true,
            position: 'top-right',
            icon: 'error',
            title: "{{ addslashes(session('error') ?? session('danger')) }}",
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true
        });
    @endif
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

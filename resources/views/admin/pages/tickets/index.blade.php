@extends('admin.layouts.master')
@section('title')
<title>{{ config('app.name') }} | {{ __('tickets.tickets') }}</title>
@endsection
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h1 class="bold mb-0 mt-1 text-dark">
                    <i data-feather="help-circle" class="font-medium-2"></i>
                    <span>{{ __('tickets.tickets') }}</span>
                </h1>
            </div>
        </div>
    </div>
    <div class="content-header-right text-md-end col-md-6 col-12 d-md-block">
        <div class="mb-1 breadcrumb-right">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary me-1 waves-effect" data-bs-toggle="dropdown">
                    <i data-feather="filter"></i>
                    <span class="active-sorting text-primary">{{ __('tickets.filter') }}</span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" data-filter="all">{{ __('tickets.statuses.all') }}</a>
                    <a class="dropdown-item" href="#" data-filter="open">{{ __('tickets.statuses.open') }}</a>
                    <a class="dropdown-item" href="#" data-filter="in_progress">{{ __('tickets.statuses.in_progress') }}</a>
                    <a class="dropdown-item" href="#" data-filter="resolved">{{ __('tickets.statuses.resolved') }}</a>
                    <a class="dropdown-item" href="#" data-filter="closed">{{ __('tickets.statuses.closed') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="dt-multilingual table datatables-ajax">
                <thead>
                    <tr>
                        <th>{{ __('tickets.id') }}</th>
                        <th>{{ __('tickets.title') }}</th>
                        <th>{{ __('tickets.customer') }}</th>
                        <th>{{ __('tickets.status') }}</th>
                        <th>{{ __('tickets.priority') }}</th>
                        <th>{{ __('tickets.assigned_to') }}</th>
                        <th>{{ __('tickets.replies') }}</th>
                        <th>{{ __('tickets.unread') }}</th>
                        <th>{{ __('tickets.created') }}</th>
                        <th width="15%" class="text-center">{{ __('tickets.options') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Assign Ticket Modal -->
<div class="modal fade text-start" id="assignTicketModal" tabindex="-1" aria-labelledby="assignTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="assignTicketForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="assignTicketModalLabel">{{ __('tickets.actions.assign') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-1">
                        <label for="assigned_to" class="form-label">{{ __('tickets.actions.assign_to_admin') }}</label>
                        <select name="assigned_to" id="assigned_to" class="form-select" required>
                            <option value="">{{ __('tickets.actions.select_admin') }}</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success">{{ __('tickets.actions.assign') }}</button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">{{ __('tickets.actions.cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@push('scripts')
<script>
    var dt_ajax_table = $('.datatables-ajax');
    var dt_ajax = dt_ajax_table.dataTable({
        processing: true,
        serverSide: true,
        searching: true,
        paging: true,
        info: false,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        },
        ajax: {
            url: "{{ route('admin.tickets.index') }}"
        },
        drawCallback: function(settings) {
            feather.replace();
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'title', name: 'title' },
            { data: 'user_name', name: 'user_name' },
            { data: 'status_badge', name: 'status' },
            { data: 'priority_badge', name: 'priority' },
            { data: 'assigned_to_name', name: 'assigned_to_name' },
            { data: 'replies_count', name: 'replies_count' },
            { data: 'unread_count', name: 'unread_count' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        columnDefs: [
            {
                targets: [3, 4, 6, 7, 9],
                render: function(data, type, row) {
                    return data;
                }
            }
        ],
        order: [[0, 'desc']]
    });

    // Filter functionality
    $('[data-filter]').on('click', function(e) {
        e.preventDefault();
        var filter = $(this).data('filter');
        
        if (filter === 'all') {
            dt_ajax.DataTable().search('').draw();
        } else {
            dt_ajax.DataTable().search(filter).draw();
        }
        
        // Update active filter display
        $('.active-sorting').text($(this).text());
    });

    // Assign ticket modal
    $(document).on('click', '.assign-ticket', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        $('#assignTicketForm').attr('action', url);
        $('#assignTicketModal').modal('show');
    });

    // Assign ticket form submission
    $('#assignTicketForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'PATCH',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#assignTicketModal').modal('hide');
                    dt_ajax.DataTable().ajax.reload();
                    toastr.success(response.message);
                    $('#assignTicketForm')[0].reset();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var message = xhr.responseJSON.message || '{{ __('tickets.messages.validation_error') }}';
                    toastr.error(message);
                } else {
                    toastr.error('{{ __('tickets.messages.error_occurred') }}');
                }
            }
        });
    });
</script>
@endpush

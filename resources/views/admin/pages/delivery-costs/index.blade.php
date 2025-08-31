@extends('admin.layouts.master')
@section('title')
<title>{{ config('app.name') }} | {{ __('delivery.title') }} - {{ $supplier->name }}</title>
@endsection
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h1 class="bold mb-0 mt-1 text-dark">
                    <i data-feather="truck" class="font-medium-2"></i>
                    <span>{{ __('delivery.title') }} - {{ $supplier->name }}</span>
                </h1>
            </div>
        </div>
    </div>
    <div class="content-header-right text-md-end col-md-6 col-12 d-md-block">
        <div class="mb-1 breadcrumb-right">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary me-1 waves-effect" data-bs-toggle="modal" data-bs-target="#addDeliveryCostModal">
                    <i data-feather="plus"></i>
                    <span class="active-sorting text-primary">{{ __('delivery.actions.add') }}</span>
                </button>
                <a class="btn btn-sm btn-outline-secondary waves-effect" href="{{ route('admin.suppliers.index') }}">
                    <i data-feather="arrow-left"></i>
                    <span>{{ __('delivery.back_to_suppliers') }}</span>
                </a>
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
                        <th>{{ __('delivery.city') }}</th>
                        <th>{{ __('delivery.cost') }}</th>
                        <th>{{ __('delivery.status') }}</th>
                        <th>{{ __('delivery.notes') }}</th>
                        <th>{{ __('delivery.created_at') }}</th>
                        <th width="15%" class="text-center">{{ __('delivery.actions.title') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Add Delivery Cost Modal -->
<div class="modal fade text-start" id="addDeliveryCostModal" tabindex="-1" aria-labelledby="addDeliveryCostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addDeliveryCostForm" method="POST" action="{{ route('admin.delivery-costs.store', $supplier->id) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addDeliveryCostModalLabel">{{ __('delivery.actions.add') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-1 col-12">
                            <label for="city_id" class="form-label">{{ __('delivery.city') }} <span class="text-danger">*</span></label>
                            <select name="city_id" id="city_id" class="form-select" required>
                                <option value="">{{ __('delivery.select_city') }}</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">
                                        {{ $city->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1 col-12">
                            <label for="cost" class="form-label">{{ __('delivery.cost') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="cost" id="cost" class="form-control" placeholder="{{ __('delivery.cost_placeholder') }}" required>
                        </div>
                        <div class="mb-1 col-12">
                            <label for="notes" class="form-label">{{ __('delivery.notes') }}</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="{{ __('delivery.notes_placeholder') }}"></textarea>
                        </div>
                        <div class="mb-1 col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active" value="1" id="active" checked>
                                <label class="form-check-label" for="active">{{ __('delivery.active') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="saveDeliveryCost" class="btn btn-sm btn-success">{{ __('delivery.actions.save') }}</button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">{{ __('delivery.actions.cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Delivery Cost Modal -->
<div class="modal fade text-start" id="editDeliveryCostModal" tabindex="-1" aria-labelledby="editDeliveryCostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editDeliveryCostForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editDeliveryCostModalLabel">{{ __('delivery.actions.edit') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-1 col-12">
                            <label for="edit_cost" class="form-label">{{ __('delivery.cost') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="cost" id="edit_cost" class="form-control" required>
                        </div>
                        <div class="mb-1 col-12">
                            <label for="edit_notes" class="form-label">{{ __('delivery.notes') }}</label>
                            <textarea name="notes" id="edit_notes" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-1 col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active" value="1" id="edit_active">
                                <label class="form-check-label" for="edit_active">{{ __('delivery.active') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="saveEditDeliveryCost" class="btn btn-sm btn-success">{{ __('delivery.actions.update') }}</button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">{{ __('delivery.actions.cancel') }}</button>
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
            [10, 50, 100, 500, -1],
            [10, 50, 100, 500, "All"]
        ],
        language: {
            paginate: {
                previous: '&nbsp;',
                next: '&nbsp;'
            }
        },
        ajax: {
            url: "{{ route('admin.delivery-costs.index', $supplier->id) }}"
        },
        drawCallback: function(settings) {
            feather.replace();
        },
        columns: [
            { data: 'city_name', name: 'city_name' },
            { data: 'cost_formatted', name: 'cost' },
            { data: 'status', name: 'active' },
            { data: 'notes', name: 'notes' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        columnDefs: [
            {
                targets: -1,
                render: function(data, type, row) {
                    return data;
                }
            }
        ]
    });

    // Add delivery cost form submission
    $('#saveDeliveryCost').on('click', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $("#addDeliveryCostForm").attr('action'),
            method: 'POST',
            data: $("#addDeliveryCostForm").serialize(),
            success: function(response) {
                if (response.success) {
                    $('#addDeliveryCostModal').modal('hide');
                    dt_ajax.DataTable().ajax.reload();
                    toastr.success(response.message);
                    $('#addDeliveryCostForm')[0].reset();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var message = xhr.responseJSON.message || '{{ __("delivery.messages.validation_error") }}';
                    toastr.error(message);
                } else {
                    toastr.error('{{ __("delivery.messages.error") }}');
                }
            }
        });
    });

    // Edit delivery cost
    $(document).on('click', '.edit-delivery-cost', function() {
        var id = $(this).data('id');
        var cost = $(this).data('cost');
        var notes = $(this).data('notes');
        var active = $(this).data('active');
        var updateUrl = $(this).data('url');

        $('#edit_cost').val(cost);
        $('#edit_notes').val(notes);
        $('#edit_active').prop('checked', active == 1);
        $('#editDeliveryCostForm').attr('action', updateUrl);
        $('#editDeliveryCostModal').modal('show');
    });

    // Edit delivery cost form submission
    $('#saveEditDeliveryCost').on('click', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $('#editDeliveryCostForm').attr('action'),
            method: 'PUT',
            data: $('#editDeliveryCostForm').serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editDeliveryCostModal').modal('hide');
                    dt_ajax.DataTable().ajax.reload();
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('{{ __("delivery.messages.error") }}');
            }
        });
    });

    // Delete delivery cost
    $(document).on('click', '.delete_item', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        
        Swal.fire({
            title: '{{ __("delivery.messages.delete_confirm") }}',
            text: '{{ __("delivery.messages.delete_warning") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __("delivery.actions.delete") }}',
            cancelButtonText: '{{ __("delivery.actions.cancel") }}',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-outline-secondary ms-1'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            dt_ajax.DataTable().ajax.reload();
                            toastr.success(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('{{ __("delivery.messages.error") }}');
                    }
                });
            }
        });
    });
</script>
@endpush

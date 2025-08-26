@extends('admin.layouts.master')

@section('title')
<title>{{ config('app.name') }} | {{ __('security.audit_trail') }}</title>
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">{{ __('security.audit_trail') }}</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('admin.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.security.dashboard') }}">{{ __('security.security') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('security.audit_trail') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('security.user_activity_logs') }}</h4>
                </div>

                <!-- Filters -->
                <div class="card-body border-bottom">
                    <form id="filterForm" class="row">
                        <div class="col-md-2">
                            <label for="user_id">{{ __('security.user') }}</label>
                            <select class="form-control select2" id="user_id" name="user_id">
                                <option value="">{{ __('security.all_users') }}</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="action_type">{{ __('security.action_type') }}</label>
                            <select class="form-control" id="action_type" name="action_type">
                                <option value="">{{ __('security.all_actions') }}</option>
                                @foreach($actionTypes as $actionType)
                                <option value="{{ $actionType }}">{{ ucfirst($actionType) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="model_type">{{ __('security.model_type') }}</label>
                            <select class="form-control" id="model_type" name="model_type">
                                <option value="">{{ __('security.all_models') }}</option>
                                <option value="App\Models\User">User</option>
                                <option value="App\Models\Trip">Trip</option>
                                <option value="App\Models\Order">Order</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="date_from">{{ __('security.date_from') }}</label>
                            <input type="date" class="form-control" id="date_from" name="date_from">
                        </div>

                        <div class="col-md-2">
                            <label for="date_to">{{ __('security.date_to') }}</label>
                            <input type="date" class="form-control" id="date_to" name="date_to">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-primary mr-1" onclick="filterTable()">
                                <i data-feather="search"></i> {{ __('security.filter') }}
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                <i data-feather="x"></i> {{ __('security.clear') }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="auditTrailTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('security.user') }}</th>
                                    <th>{{ __('security.email') }}</th>
                                    <th>{{ __('security.action') }}</th>
                                    <th>{{ __('security.description') }}</th>
                                    <th>{{ __('security.model') }}</th>
                                    <th>{{ __('security.changes') }}</th>
                                    <th>{{ __('security.ip_address') }}</th>
                                    <th>{{ __('security.date_time') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#auditTrailTable').DataTable({
            processing: true
            , serverSide: true
            , ajax: {
                url: "{{ route('admin.security.audit-trail.data') }}"
                , data: function(d) {
                    d.user_id = $('#user_id').val();
                    d.action_type = $('#action_type').val();
                    d.model_type = $('#model_type').val();
                    d.date_from = $('#date_from').val();
                    d.date_to = $('#date_to').val();
                }
            }
            , columns: [{
                    data: 'DT_RowIndex'
                    , name: 'DT_RowIndex'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'user_name'
                    , name: 'user.name'
                }
                , {
                    data: 'user_email'
                    , name: 'user.email'
                }
                , {
                    data: 'action_type'
                    , name: 'action_type'
                }
                , {
                    data: 'description'
                    , name: 'description'
                }
                , {
                    data: 'model_info'
                    , name: 'model_info'
                    , orderable: false
                }
                , {
                    data: 'changes'
                    , name: 'changes'
                    , orderable: false
                }
                , {
                    data: 'ip_address'
                    , name: 'ip_address'
                }
                , {
                    data: 'formatted_date'
                    , name: 'created_at'
                }
            ]
            , order: [
                [8, 'desc']
            ]
            , pageLength: 25
            , responsive: true
            , dom: 'Bfrtip'
            , buttons: [{
                    extend: 'excel'
                    , text: "<i data-feather=\"download\"></i> {{ __('security.export_excel ') }}"
                    , className: 'btn btn-success btn-sm'
                }
                , {
                    extend: 'pdf'
                    , text: "<i data-feather=\"file-text\"></i> {{ __('security.export_pdf ') }}"
                    , className: 'btn btn-danger btn-sm'
                }
            ]
        });

        // Initialize Select2
        $('.select2').select2({
            placeholder: "{{ __('security.user') }}"
            , allowClear: true
        });
    });

    function filterTable() {
        $('#auditTrailTable').DataTable().ajax.reload();
    }

    function clearFilters() {
        $('#filterForm')[0].reset();
        $('.select2').val(null).trigger('change');
        $('#auditTrailTable').DataTable().ajax.reload();
    }

</script>
@endpush
@endsection

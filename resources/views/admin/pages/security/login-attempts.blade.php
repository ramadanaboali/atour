@extends('admin.layouts.master')

@section('title')
<title>{{ config('app.name') }} | {{ __('security.login_attempts') }}</title>
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">{{ __('security.login_attempts') }}</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('admin.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.security.dashboard') }}">{{ __('security.security') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('security.login_attempts') }}</li>
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
                    <h4 class="card-title">{{ __('security.login_attempts_log') }}</h4>
                </div>
                
                <!-- Filters -->
                <div class="card-body border-bottom">
                    <form id="filterForm" class="row">
                        <div class="col-md-3">
                            <label for="email">{{ __('security.email') }}</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('security.search_by_email') }}">
                        </div>
                        
                        <div class="col-md-2">
                            <label for="successful">{{ __('security.status') }}</label>
                            <select class="form-control" id="successful" name="successful">
                                <option value="">{{ __('security.all_attempts') }}</option>
                                <option value="true">{{ __('security.successful') }}</option>
                                <option value="false">{{ __('security.failed') }}</option>
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
                        
                        <div class="col-md-3 d-flex align-items-end">
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
                        <table class="table table-striped" id="loginAttemptsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('security.email') }}</th>
                                    <th>{{ __('security.user') }}</th>
                                    <th>{{ __('security.status') }}</th>
                                    <th>{{ __('security.failure_reason') }}</th>
                                    <th>{{ __('security.location_info') }}</th>
                                    <th>{{ __('security.user_agent') }}</th>
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
    var table = $('#loginAttemptsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.security.login-attempts.data') }}",
            data: function(d) {
                d.email = $('#email').val();
                d.successful = $('#successful').val();
                d.date_from = $('#date_from').val();
                d.date_to = $('#date_to').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'email', name: 'email'},
            {data: 'user_name', name: 'user.name'},
            {data: 'status', name: 'successful'},
            {data: 'failure_info', name: 'failure_reason'},
            {data: 'location_info', name: 'location_info', orderable: false},
            {data: 'user_agent', name: 'user_agent'},
            {data: 'formatted_date', name: 'attempted_at'}
        ],
        order: [[7, 'desc']],
        pageLength: 25,
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i data-feather="download"></i> {{ __('security.export_excel') }}',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdf',
                text: '<i data-feather="file-text"></i> {{ __('security.export_pdf') }}',
                className: 'btn btn-danger btn-sm'
            }
        ]
    });
});

function filterTable() {
    $('#loginAttemptsTable').DataTable().ajax.reload();
}

function clearFilters() {
    $('#filterForm')[0].reset();
    $('#loginAttemptsTable').DataTable().ajax.reload();
}
</script>
@endpush
@endsection

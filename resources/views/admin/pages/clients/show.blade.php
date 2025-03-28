@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('clients.plural') }}</title>
@endsection
@section('content')

    <div class="content-header row">
    </div>
    <div class="content-body">
        <section class="app-user-view-account">
            <div class="row">
                <!-- User Sidebar -->
                <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                    <!-- User Card -->
                    <div class="card">
                        <div class="card-body">
                            <div class="user-avatar-section">
                                <div class="d-flex align-items-center flex-column">
                                    <img class="img-fluid rounded mt-3 mb-2" src="{{ $item->photo }}" height="110" width="110" alt="{{ $item->name }}" />
                                    <div class="user-info text-center">
                                        <h4>{{ $item->name }}</h4>
                                        <span class="badge bg-light-secondary">{{ $item->code }}</span>

                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-around my-2 pt-75">
                                <div class="d-flex align-items-start me-2">
                                    <span class="badge bg-light-primary p-75 rounded">
                                        <i data-feather="check" class="font-medium-2"></i>
                                    </span>
                                    <div class="ms-75">
                                        <h4 class="mb-0">{{ count($compleated_orders) }}</h4>
                                        <small>{{ __('admin.orders_done') }}</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-light-primary p-75 rounded">
                                        <i data-feather="briefcase" class="font-medium-2"></i>
                                    </span>
                                    <div class="ms-75">
                                        <h4 class="mb-0">{{ count($pendding_orders) }}</h4>
                                        <small>{{ __('admin.pendding_orders') }}</small>
                                    </div>
                                </div>
                            </div>
                            <h4 class="fw-bolder border-bottom pb-50 mb-1">{{ __('clients.details') }}</h4>
                            <div class="info-container">
                                <ul class="list-unstyled">
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('clients.phone') }} : </span>
                                        <span> {{ $item->phone }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('clients.email') }} : </span>
                                        <span>{{ $item->email }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('clients.status') }} : </span>
                                        <span class="badge bg-light-success">{{ __('clients.statuses.'.$item->status) }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('clients.birthdate') }}:</span>
                                        <span>{{ $item->birthdate }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('clients.joining_date_from') }}:</span>
                                        <span>{{ $item->joining_date_from }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('clients.nationality') }}:</span>
                                        <span>{{ $item->nationality }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('clients.address') }}:</span>
                                        <span>{{ $item->address }}</span>
                                    </li>

                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('clients.city') }}:</span>
                                        <span>{{ $item->city?->name }}</span>
                                    </li>

                                </ul>
                                <div class="d-flex justify-content-center pt-2">
                                    <a href="{{ route('admin.clients.edit',['id'=>$item->id]) }}" class="btn btn-primary me-1" >
                                        {{ __('clients.actions.edit') }}
                                    </a>
                                    <a  class="btn btn-outline-danger suspend-user client_status" data-url="{{ route("admin.clients.status", ['id'=>$item->id]) }}" href="#">{{ __('clients.actions.status') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /User Card -->
                </div>
                <!--/ User Sidebar -->

                <!-- User Content -->
                <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">

                    <!-- Activity Timeline -->
                    <div class="card">
                        <h4 class="card-header">{{ __('clients.login_activity') }}</h4>
                        <div class="card-body pt-1">
                            <ul class="timeline ms-50">
                                <li class="timeline-item">
                                    <span class="timeline-point timeline-point-indicator"></span>
                                    <div class="timeline-event">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                            <h6>{{ __('clients.last_login') }}</h6>
                                            <span class="me-1">{{ $item->last_login }}</span>
                                        </div>

                                    </div>
                                </li>
                                <li class="timeline-item">
                                    <span class="timeline-point timeline-point-warning timeline-point-indicator"></span>
                                    <div class="timeline-event">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                            <h6>{{ __('clients.login_with_social_media') }}</h6>
                                            <span class="me-1">{{ __('clients.false') }}</span>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <!-- /Activity Timeline -->

                    <!-- Order table -->
                    <div class="card">
                        <h4 class="card-header">{{ __('clients.orders_details') }}</h4>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="invoice-table table text-nowrap datatables-orders">
                                    <thead>
                                        <tr>
                                            <th>{{ __('orders.vendor') }}</th>
                                            <th>{{ __('orders.order_date') }}</th>
                                            <th>{{ __('orders.total') }}</th>
                                            <th>{{ __('admin.admin_value') }}</th>

                                            <th>{{ __('orders.status') }}</th>
                                            <th>{{ __('orders.type') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /Order table -->
                    <!-- Rates table -->
                    <div class="card">
                        <h4 class="card-header">{{ __('clients.rates_details') }}</h4>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="invoice-table table text-nowrap datatables-rates">
                                    <thead>
                                        <tr>
                                            <th>{{ __('orders.rate') }}</th>
                                            <th>{{ __('orders.vendor') }}</th>
                                            <th>{{ __('orders.comment') }}</th>

                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /Rates table -->
                    <!-- Rates table -->
                    <div class="card">
                        <h4 class="card-header">{{ __('clients.favourit_details') }}</h4>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="invoice-table table text-nowrap datatables-favorite">
                                    <thead>
                                        <tr>
                                            <th>{{ __('orders.trip') }}</th>
                                            <th>{{ __('orders.created_at') }}</th>

                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /Rates table -->
                </div>
                <!--/ User Content -->
            </div>
        </section>


    </div>

@stop
@push('scripts')
    <script>
        const statuses = @json($status); 
        var dt_ajax_table = $('.datatables-orders');
        var dt_ajax = dt_ajax_table.dataTable({
            processing: true,
            serverSide: true,
            searching: true,
            paging: true,
            info: false,
            lengthMenu: [[10, 50, 100,500, -1], [10, 50, 100,500, "All"]],
            language: {
                paginate: {
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            ajax: {
                url: "{{ route('admin.orders.list') }}",
                data: function (d) {
                    d.user_id  = {{ $item->id }};
                    d.status= statuses;
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                /*{data: 'DT_RowIndex', name: 'DT_RowIndex'},*/
                {data: 'vendor', name: 'vendor',orderable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'total', name: 'total'},
                {data: 'admin_value', name: 'admin_value'},
                {data: 'status', name: 'status'},
                {data: 'source', name: 'source'},
            ],
            columnDefs: [


            ],
        });
        var dt_ajax_rate = $('.datatables-rates');
        var dt_rate = dt_ajax_rate.dataTable({
            processing: true,
            serverSide: true,
            searching: true,
            paging: true,
            info: false,
            lengthMenu: [[10, 50, 100,500, -1], [10, 50, 100,500, "All"]],
            language: {
                paginate: {
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            ajax: {
                url: "{{ route('admin.rates.list') }}",
                data: function (d) {
                    d.user_id  = {{ $item->id }};
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                /*{data: 'DT_RowIndex', name: 'DT_RowIndex'},*/
                {data: 'rate', name: 'rate'},
                {data: 'comment', name: 'comment'},
                {data: 'vendor', name: 'vendor'},

            ],
            columnDefs: [


            ],
        });
        var dt_ajax_favorite = $('.datatables-favorite');
        var dt_favorite = dt_ajax_favorite.dataTable({
            processing: true,
            serverSide: true,
            searching: true,
            paging: true,
            info: false,
            lengthMenu: [[10, 50, 100,500, -1], [10, 50, 100,500, "All"]],
            language: {
                paginate: {
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            ajax: {
                url: "{{ route('admin.favorites.list') }}",
                data: function (d) {
                    d.user_id  = {{ $item->id }};
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                /*{data: 'DT_RowIndex', name: 'DT_RowIndex'},*/
                {data: 'trip', name: 'trip'},
                {data: 'created_at', name: 'created_at'},

            ],
            columnDefs: [


            ],
        });

    </script>
@endpush

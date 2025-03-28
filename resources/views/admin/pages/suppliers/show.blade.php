@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('suppliers.plural') }}</title>
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
                                    <img class="img-fluid rounded mt-3 mb-2" src="{{ $user?->photo }}" height="110" width="110" alt="{{ $user?->name }}" />
                                    <div class="user-info text-center">
                                        <h4>{{ $user?->name }}</h4>

                                    </div>
                                </div>
                            </div>

                            <h4 class="fw-bolder border-bottom pb-50 mb-1">{{ __('clients.details') }}</h4>
                            <div class="info-container">
                                <ul class="list-unstyled">
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('clients.phone') }} : </span>
                                        <span> {{ $user?->phone }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('clients.email') }} : </span>
                                        <span>{{ $user?->email }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('clients.status') }} : </span>
                                        <span class="badge bg-light-success">{{ __('clients.statuses.'.$user?->status) }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('clients.birthdate') }}:</span>
                                        <span>{{ $user?->birthdate }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('clients.joining_date_from') }}:</span>
                                        <span>{{ $user?->joining_date_from }}</span>
                                    </li>


                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('suppliers.country') }}:</span>
                                        <span>{{ $user->supplier?->country?->name }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('suppliers.city') }}:</span>
                                        <span>{{ $user->supplier?->city?->name }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('suppliers.streat') }}:</span>
                                        <span>{{ $user->supplier?->streat }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('suppliers.postal_code') }}:</span>
                                        <span>{{ $user->supplier?->postal_code }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('suppliers.url') }}:</span>
                                        <span>{{ $user->supplier?->url }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('suppliers.tax_number') }}:</span>
                                        <span>{{ $user->supplier?->tax_number }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('suppliers.place_summary') }}:</span>
                                        <span>{{ $user->supplier?->place_summary }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('suppliers.place_content') }}:</span>
                                        <span>{{ $user->supplier?->place_content }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('suppliers.expectations') }}:</span>
                                        <span>{{ $user->supplier?->expectations }}</span>
                                    </li>

                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('suppliers.short_description') }}:</span>
                                        <span>{{ $user->supplier?->short_description }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('suppliers.description') }}:</span>
                                        <span>{{ $user->supplier?->description }}</span>
                                    </li>

                                </ul>
                                <div class="d-flex justify-content-center pt-2">

                                   
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
                        <h4 class="card-header">{{ __('suppliers.suppliers_experince') }}</h4>
                        <div class="card-body pt-1">
                            <ul class="timeline ms-50">
                                <li class="timeline-item">
                                    <span class="timeline-point timeline-point-indicator"></span>
                                    <div class="timeline-event">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                            <h6>{{ __('suppliers.is_profissional_guid') }}</h6>
                                            <span class="me-1">{{ $user->supplier?->profission_guide?__('admin.true'):__('admin.false') }}</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-item">
                                    <span class="timeline-point timeline-point-warning timeline-point-indicator"></span>
                                    <div class="timeline-event">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                            <h6>{{ __('suppliers.whats_your_job') }}</h6>
                                            <span class="me-1">{{ $user->supplier?->job }}</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-item">
                                    <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
                                    <div class="timeline-event">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                            <h6>{{ __('suppliers.how_get_inforamtion') }}</h6>
                                            <span class="me-1">{{ $user->supplier?->experience_info }}</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-item">
                                    <span class="timeline-point timeline-point-success timeline-point-indicator"></span>
                                    <div class="timeline-event">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                            <h6>{{ __('suppliers.languages') }}</h6>
                                            <span class="me-1">{{ $user->supplier?->languages }}</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-item">
                                    <span class="timeline-point timeline-point-danger timeline-point-indicator"></span>
                                    <div class="timeline-event">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                        <h6>{{ __('suppliers.account_details') }}</h6>
                                        </div>
                                        <div class="d-flex flex-row align-items-center mt-50">
                                            <h6 class="mb-0">{{ __('suppliers.banck_name') }} : </h6>
                                            <h6 class="mb-0">{{ $user->supplier?->bank_name }}</h6>
                                        </div>
                                        <div class="d-flex flex-row align-items-center mt-50">
                                            <h6 class="mb-0">{{ __('suppliers.banck_number') }} : </h6>
                                            <h6 class="mb-0"> {{ $user->supplier?->bank_account }}</h6>
                                        </div>
                                    </div>
                                </li>
                                <hr>

                                <hr>
                                <li class="timeline-item">
                                    <span class="timeline-point timeline-point-indicator"></span>
                                    <div class="timeline-event">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                            <h6>{{ __('clients.last_login') }}</h6>
                                            <span class="me-1">{{ $user?->last_login }}</span>
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
                                            <th>{{ __('orders.client') }}</th>
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
                    <div class="card">
                        <h4 class="card-header">{{ __('orders.trips') }}</h4>
                        <div class="card-body">
                            <div class="table-responsive">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="py-1">{{ __('orders.client_name') }}</th>
                                            <th class="py-1">{{ __('orders.description') }}</th>
                                            <th class="py-1">{{ __('orders.total') }}</th>
                                            <th class="py-1">{{ __('orders.phone') }}</th>
                                            <th class="py-1">{{ __('orders.start_point') }}</th>
                                            <th class="py-1">{{ __('orders.end_point') }}</th>
                                            <th class="py-1">{{ __('orders.cancelation_policy') }}</th>
                                            <th class="py-1">{{ __('orders.free_cancelation') }}</th>
                                            <th class="py-1">{{ __('orders.pay_later') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trips as $trip)

                                        <tr>

                                            <td class="py-1">
                                                <span class="fw-bold">{{ $trip->title }}</span>
                                                <span class="fw-bold">{{ $trip->description }}</span>
                                                <span class="fw-bold">{{ $trip->price }}</span>
                                                <span class="fw-bold">{{ $trip->phone }}</span>
                                                <span class="fw-bold">{{ $trip->start_point }}</span>
                                                <span class="fw-bold">{{ $trip->end_point }}</span>
                                                <span class="fw-bold">{{ $trip->cancelation_policy }}</span>
                                                <span class="fw-bold">{{ $trip->free_cancelation }}</span>
                                                <span class="fw-bold">{{ $trip->pay_later }}</span>
                                            </td>

                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <!-- /Rates table -->
                    <div class="card">
                        <h4 class="card-header">{{ __('suppliers.images') }}</h4>
                        <div class="card-body">
                            @foreach ($user?->photos as $photo)
                                <div class="col-md-4">
                                    <img src="{{ $photo->file }}" alt="">
                                </div>
                            @endforeach
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
                url: "{{ route('admin.suppliers.orders') }}",
                data: function (d) {
                    d.user_id  = {{ $user->id }};
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                {data: 'client', name: 'client',orderable: false},
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
                    d.user_id  = {{ $user->id }};
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
                    d.user_id  = {{ $user->id }};
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

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
                                        <span class="fw-bolder me-25">{{ __('clients.joining_date_from') }}:</span>
                                        <span>{{ $user?->created_at?->format('Y-m-d') }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('suppliers.city') }}:</span>
                                        <span>{{ $user->city?->title }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('suppliers.tax_number') }}:</span>
                                        <span>{{ $user->tax_number }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">{{ __('suppliers.national_id') }}:</span>

                                        <span>{{ $user->supplier?->national_id }}</span>

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
                                            <h6 class="mb-0">{{ $user->bank_name }}</h6>
                                        </div>
                                        <div class="d-flex flex-row align-items-center mt-50">
                                            <h6 class="mb-0">{{ __('suppliers.banck_number') }} : </h6>
                                            <h6 class="mb-0"> {{ $user->bank_account }}</h6>
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

                                                <td>{{ $trip->title }}</td>
                                                <td>{{ $trip->description }}</td>
                                                <td>{{ $trip->price }}</td>
                                                <td>{{ $trip->start_point }}</td>
                                                <td>{{ $trip->end_point }}</td>
                                                <td>{{ $trip->cancelation_policy }}</td>
                                                <td>{{ $trip->free_cancelation==1?'✅':'❌' }}</td>
                                                <td>{{ $trip->pay_later==1?'✅':'❌' }}</td>

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

                    <!-- Customer Ratings Section -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('ratings.supplier_ratings') }}</h4>
                        </div>
                        <div class="card-body">
                            <!-- Rating Statistics -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h2 class="text-primary mb-1">{{ number_format($ratingStats['average_rating'], 1) }}</h2>
                                        <div class="mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= round($ratingStats['average_rating']) ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="text-muted mb-0">{{ __('ratings.average_rating') }}</p>
                                        <small class="text-muted">{{ $ratingStats['total_ratings'] }} {{ __('ratings.total_ratings') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <h6 class="mb-3">{{ __('ratings.rating_distribution') }}</h6>
                                    @for($i = 5; $i >= 1; $i--)
                                        @php
                                            $count = $ratingStats['rating_distribution'][$i] ?? 0;
                                            $percentage = $ratingStats['total_ratings'] > 0 ? ($count / $ratingStats['total_ratings']) * 100 : 0;
                                        @endphp
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="me-2">{{ $i }}</span>
                                            <i class="fas fa-star text-warning me-2"></i>
                                            <div class="progress flex-grow-1 me-3" style="height: 8px;">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="text-muted small">{{ $count }}</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- Filters -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('ratings.filter_by_rating') }}</label>
                                    <select id="rating-filter" class="form-select">
                                        <option value="">{{ __('ratings.all_ratings') }}</option>
                                        <option value="5">5 {{ __('ratings.stars') }}</option>
                                        <option value="4">4 {{ __('ratings.stars') }}</option>
                                        <option value="3">3 {{ __('ratings.stars') }}</option>
                                        <option value="2">2 {{ __('ratings.stars') }}</option>
                                        <option value="1">1 {{ __('ratings.star') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('ratings.filter_by_period') }}</label>
                                    <select id="period-filter" class="form-select">
                                        <option value="">{{ __('ratings.all_time') }}</option>
                                        <option value="week">{{ __('ratings.last_week') }}</option>
                                        <option value="month">{{ __('ratings.last_month') }}</option>
                                        <option value="3months">{{ __('ratings.last_3_months') }}</option>
                                        <option value="year">{{ __('ratings.last_year') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="button" id="reset-filters" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo me-1"></i>{{ __('ratings.reset_filters') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Ratings Table -->
                            <div class="table-responsive">
                                <table id="ratings-table" class="table table-striped datatables-ratings">
                                    <thead>
                                        <tr>
                                            <th>{{ __('ratings.customer') }}</th>
                                            <th>{{ __('ratings.rating') }}</th>
                                            <th>{{ __('ratings.service') }}</th>
                                            <th>{{ __('ratings.comment') }}</th>
                                            <th>{{ __('ratings.date') }}</th>
                                            <th>{{ __('ratings.status') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /Customer Ratings Section -->

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

        // Ratings DataTable
        var dt_ratings_table = $('.datatables-ratings');
        var dt_ratings = dt_ratings_table.dataTable({
            processing: true,
            serverSide: true,
            searching: true,
            paging: true,
            info: false,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                paginate: {
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            ajax: {
                url: "{{ route('admin.suppliers.ratings') }}",
                data: function (d) {
                    d.user_id = {{ $user->id }};
                    d.rating_filter = $('#rating-filter').val();
                    d.period_filter = $('#period-filter').val();
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                {data: 'customer_name', name: 'customer_name', orderable: false},
                {data: 'stars', name: 'rating', orderable: true},
                {data: 'service_info', name: 'service_info', orderable: false},
                {data: 'comment', name: 'comment', orderable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'verification_status', name: 'is_verified', orderable: false}
            ],
            order: [[4, 'desc']] // Order by date descending
        });

        // Filter handlers
        $('#rating-filter, #period-filter').on('change', function() {
            dt_ratings.api().ajax.reload();
        });

        $('#reset-filters').on('click', function() {
            $('#rating-filter, #period-filter').val('');
            dt_ratings.api().ajax.reload();
        });

    </script>
@endpush

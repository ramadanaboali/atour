@extends('admin.layouts.master')

@section('title')
<title>{{ config('app.name') }} | {{ __('clients.plural') }}</title>
@endsection

@section('content')
<div class="content-body">
    <section class="app-user-view-account">
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <div class="card">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded mt-3 mb-2" src="{{ $item->photo }}" height="110" width="110" alt="{{ $item->name }}" />
                                <div class="user-info text-center">
                                    <h4>{{ $item->name }}</h4>
                                    <span class="badge bg-light-secondary">C-{{ $item->code }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-around my-2 pt-75">
                            <div class="d-flex align-items-start me-2">
                                <span class="badge bg-light-primary p-75 rounded">
                                    <i data-feather="check" class="font-medium-2"></i>
                                </span>
                                <div class="ms-75">
                                    <h4 class="mb-0">{{ count($completedOrders) }}</h4>
                                    <small>{{ __('admin.orders_done') }}</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <span class="badge bg-light-primary p-75 rounded">
                                    <i data-feather="briefcase" class="font-medium-2"></i>
                                </span>
                                <div class="ms-75">
                                    <h4 class="mb-0">{{ count($pendingOrders) }}</h4>
                                    <small>{{ __('admin.pending_orders') }}</small>
                                </div>
                            </div>
                        </div>

                        <h4 class="fw-bolder border-bottom pb-50 mb-1">{{ __('clients.details') }}</h4>
                        <ul class="list-unstyled">
                            <li class="mb-75"><span class="fw-bolder me-25">{{ __('clients.phone') }}:</span> {{ $item->phone }}</li>
                            @if($item->email)
                            <li class="mb-75"><span class="fw-bolder me-25">{{ __('clients.email') }}:</span> {{ $item->email }}</li>
                            @endif
                            <li class="mb-75">
                                <span class="fw-bolder me-25">{{ __('clients.status') }}:</span>
                                <span class="badge bg-light-success">{{ __('clients.statuses.'.$item->status) }}</span>
                            </li>
                            <li class="mb-75"><span class="fw-bolder me-25">{{ __('clients.birthdate') }}:</span> {{ $item->birthdate }}</li>
                            <li class="mb-75"><span class="fw-bolder me-25">{{ __('clients.joining_date_from') }}:</span> {{ $item->joining_date_from }}</li>
                            <li class="mb-75"><span class="fw-bolder me-25">{{ __('clients.nationality') }}:</span> {{ $item->nationality }}</li>
                            <li class="mb-75"><span class="fw-bolder me-25">{{ __('clients.address') }}:</span> {{ $item->address }}</li>
                            <li class="mb-75"><span class="fw-bolder me-25">{{ __('clients.city') }}:</span> {{ optional($item->city)->name }}</li>
                        </ul>

                        <div class="d-flex justify-content-center pt-2">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                    <i data-feather="settings" class="font-medium-2"></i>
                                    <span>{{ __('clients.actions.actions') }}</span>
                                </button>
                                <div class="dropdown-menu">
                                    @can('clients.edit')
                                    <a class="dropdown-item" href="{{ route('admin.clients.edit', $item->id) }}">
                                        <i data-feather="edit-2" class="font-medium-2"></i>
                                        <span>{{ __('clients.actions.edit') }}</span>
                                    </a>
                                    @endcan
                                    @can('clients.delete')
                                    <a class="dropdown-item delete_item" data-url="{{ route('admin.clients.destroy', $item->id) }}" href="#">
                                        <i data-feather="trash" class="font-medium-2"></i>
                                        <span>{{ __('clients.actions.delete') }}</span>
                                    </a>
                                    @endcan
                                    @can('clients.status')
                                    <a class="dropdown-item client_status" data-url="{{ route('admin.clients.status', $item->id) }}" href="#">
                                        <i data-feather="circle" class="font-medium-2"></i>
                                        <span>{{ __('clients.actions.status') }}</span>
                                    </a>
                                    @endcan
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#sendNotificationModal">
                                        <i data-feather="bell" class="font-medium-2"></i>
                                        <span>{{ __('clients.actions.send_notification') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <!-- Activity Timeline -->


                <!-- Orders -->
                <x-client-data-table title="{{ __('clients.orders_details') }}" tableClass="datatables-orders" :headers="[
                        __('orders.vendor'),
                        __('orders.order_date'),
                        __('orders.total'),
                        __('admin.admin_value'),
                        __('orders.status'),
                        __('orders.type'),
                    ]" />

                <!-- Customer Ratings Section -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('ratings.client_ratings') }}</h4>
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
                                <select id="rating-filter-client" class="form-select">
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
                                <select id="period-filter-client" class="form-select">
                                    <option value="">{{ __('ratings.all_time') }}</option>
                                    <option value="week">{{ __('ratings.last_week') }}</option>
                                    <option value="month">{{ __('ratings.last_month') }}</option>
                                    <option value="3months">{{ __('ratings.last_3_months') }}</option>
                                    <option value="year">{{ __('ratings.last_year') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" id="reset-filters-client" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i>{{ __('ratings.reset_filters') }}
                                </button>
                            </div>
                        </div>

                        <!-- Ratings Table -->
                        <div class="table-responsive">
                            <table id="client-ratings-table" class="table table-striped datatables-client-ratings">
                                <thead>
                                    <tr>
                                        <th>{{ __('ratings.supplier') }}</th>
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

                <!-- Favorites -->
                <x-client-data-table title="{{ __('clients.favourit_details') }}" tableClass="datatables-favorite" :headers="[
                        __('orders.trip'),
                        __('orders.created_at'),
                    ]" />
                <div class="card">
                    <h4 class="card-header">{{ __('clients.login_activity') }}</h4>
                    <div class="card-body">
                        <ul class="timeline ">
                            <li class="timeline-item">
                                <span class="timeline-point timeline-point-indicator"></span>
                                <div class="timeline-event d-flex justify-content-between flex-sm-row flex-column ">
                                    <h6>{{ __('clients.last_login') }}</h6>
                                    <span>{{ $item->last_login }}</span>
                                </div>
                            </li>
                            <li class="timeline-item">
                                <span class="timeline-point timeline-point-warning timeline-point-indicator"></span>
                                <div class="timeline-event d-flex justify-content-between flex-sm-row flex-column ">
                                    <h6>{{ __('clients.login_with_social_media') }}</h6>
                                    <span>{{ $item->social_login ? __('clients.true') : __('clients.false') }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<!-- Send Notification Modal -->
<div class="modal fade" id="sendNotificationModal" tabindex="-1" aria-labelledby="sendNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendNotificationModalLabel">
                    <i data-feather="bell" class="font-medium-2"></i>
                    {{ __('clients.actions.send_notification') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendNotificationForm" action="{{ route('admin.clients.send-notification', $item->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="notification_title" class="form-label">{{ __('clients.notification_title') }}</label>
                        <input type="text" class="form-control" id="notification_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="notification_message" class="form-label">{{ __('clients.notification_message') }}</label>
                        <textarea class="form-control" id="notification_message" name="message" rows="4" required></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i data-feather="info" class="font-medium-2"></i>
                        {{ __('clients.notification_will_be_sent_to') }}: <strong>{{ $item->name }}</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('admin.cancel') }}
                    </button>
                    <button type="button" id="sendNotification" class="btn btn-primary">
                        <i data-feather="send" class="font-medium-2"></i>
                        {{ __('clients.actions.send_notification') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@push('scripts')
<script>
    const statuses = @json($status);
    const item_id = @json($item->id);
    console.log(item_id);

    function initDataTable(selector, url, extraData, columns) {
        return $(selector).DataTable({
            processing: true
            , serverSide: true
            , searching: true
            , paging: true
            , info: false
            , lengthMenu: [
                [10, 50, 100, 500, -1]
                , [10, 50, 100, 500, "All"]
            ]
            , language: {
                paginate: {
                    previous: '&nbsp;'
                    , next: '&nbsp;'
                }
            }
            , ajax: {
                url: url
                , data: function(d) {
                    if (extraData) Object.assign(d, extraData);
                }
            }
            , drawCallback: () => feather.replace()
            , columns: columns
        });
    }

    initDataTable('.datatables-orders', "{{ route('admin.clients.orders') }}", {
            user_id: item_id
            , status: statuses
        }
        , [{
                data: 'vendor'
                , name: 'vendor'
                , orderable: false
            }
            , {
                data: 'created_at'
                , name: 'created_at'
            }
            , {
                data: 'total'
                , name: 'total'
            }
            , {
                data: 'admin_value'
                , name: 'admin_value'
            }
            , {
                data: 'status'
                , name: 'status'
            }
            , {
                data: 'source'
                , name: 'source'
            }
        , ]
    );

    // Client Ratings DataTable
    var dt_client_ratings_table = $('.datatables-client-ratings');
    var dt_client_ratings = dt_client_ratings_table.dataTable({
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
            url: "{{ route('admin.clients.ratings') }}",
            data: function (d) {
                d.user_id = item_id;
                d.rating_filter = $('#rating-filter-client').val();
                d.period_filter = $('#period-filter-client').val();
            }
        },
        drawCallback: function (settings) {
            feather.replace();
        },
        columns: [
            {data: 'supplier_name', name: 'supplier_name', orderable: false},
            {data: 'stars', name: 'rating', orderable: true},
            {data: 'service_info', name: 'service_info', orderable: false},
            {data: 'comment', name: 'comment', orderable: false},
            {data: 'created_at', name: 'created_at'},
            {data: 'verification_status', name: 'is_verified', orderable: false}
        ],
        order: [[4, 'desc']] // Order by date descending
    });

    // Client ratings filter handlers
    $('#rating-filter-client, #period-filter-client').on('change', function() {
        dt_client_ratings.api().ajax.reload();
    });

    $('#reset-filters-client').on('click', function() {
        $('#rating-filter-client, #period-filter-client').val('');
        dt_client_ratings.api().ajax.reload();
    });

    initDataTable('.datatables-favorite', "{{ route('admin.favorites.list') }}", {
            user_id: item_id
        }
        , [{
                data: 'trip'
                , name: 'trip'
            }
            , {
                data: 'created_at'
                , name: 'created_at'
            }
        , ]
    );

    // Handle notification form submission
    $('#sendNotification').on('click', function(e) {
        e.preventDefault();
        
        const form = $("#sendNotificationForm");
        const submitBtn = $(this);
        const originalText = submitBtn.html();
        
        // Show loading state
        submitBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i>{{ __("admin.sending") }}...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#sendNotificationModal').modal('hide');
                    form[0].reset();
                    
                    // Show success message
                    toastr.success(response.message || '{{ __("clients.notification_sent_successfully") }}');
                } else {
                    toastr.error(response.message || '{{ __("admin.error_occurred") }}');
                }
            },
            error: function(xhr) {
                let errorMessage = '{{ __("admin.error_occurred") }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function() {
                // Reset button state
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

</script>
@endpush

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
                            @can('clients.edit')
                            <div class="btn-group">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ __('clients.actions.title') }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.clients.edit', $item->id) }}">
                                            <i data-feather="edit-2" class="me-50"></i>
                                            {{ __('clients.actions.edit') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item client_status" data-url="{{ route('admin.clients.status', ['id'=>$item->id]) }}" href="#">
                                            <i data-feather="power" class="me-50"></i>
                                            {{ __('clients.actions.status') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#sendNotificationModal">
                                            <i data-feather="bell" class="me-50"></i>
                                            {{ __('clients.send_notification') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @endcan
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

                <!-- Rates -->
                <x-client-data-table title="{{ __('clients.rates_details') }}" tableClass="datatables-rates" :headers="[
                        __('orders.rate'),
                        __('orders.vendor'),
                        __('orders.comment'),
                    ]" />

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

    initDataTable('.datatables-rates', "{{ route('admin.rates.list') }}", {
            user_id: item_id
        }
        , [{
                data: 'rate'
                , name: 'rate'
            }
            , {
                data: 'vendor'
                , name: 'vendor'
            }
            , {
                data: 'comment'
                , name: 'comment'
            }
        , ]
    );

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

</script>
@endpush

<!-- Send Notification Modal -->
<div class="modal fade" id="sendNotificationModal" tabindex="-1" aria-labelledby="sendNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendNotificationModalLabel">{{ __('clients.send_notification') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendNotificationForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="notificationTitle" class="form-label">{{ __('clients.notification_title') }}</label>
                        <input type="text" class="form-control" id="notificationTitle" name="title" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="notificationMessage" class="form-label">{{ __('clients.notification_message') }}</label>
                        <textarea class="form-control" id="notificationMessage" name="message" rows="4" required maxlength="1000"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('clients.cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="sendNotificationBtn">
                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                        {{ __('clients.send_notification') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('sendNotificationForm');
    const submitBtn = document.getElementById('sendNotificationBtn');
    const spinner = submitBtn.querySelector('.spinner-border');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        
        const formData = new FormData(form);
        
        fetch('{{ route("admin.clients.sendNotification", $item->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                form.reset();
                $('#sendNotificationModal').modal('hide');
            } else {
                toastr.error(data.message || '{{ __("admin.error_occurred") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('{{ __("admin.error_occurred") }}');
        })
        .finally(() => {
            // Hide loading state
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        });
    });
});
</script>

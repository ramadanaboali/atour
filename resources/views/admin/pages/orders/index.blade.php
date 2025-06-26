@extends('admin.layouts.master')
@section('title')
<title>{{ config('app.name') }} | {{ __('orders.plural') }}</title>
@endsection
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h1 class="bold mb-0 mt-1 text-dark">
                    <i data-feather="box" class="font-medium-2"></i>
                    <span>{{ $title ?? __('orders.plural') }}</span>
                </h1>
            </div>
        </div>
    </div>
    <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
        <div class="mb-1 breadcrumb-right">

        </div>
    </div>
</div>
<div class="content-body">
    <div class="card">
        <div class="card-datatable">
            <ul class="nav nav-tabs" id="bookingTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="gift-tab" data-bs-toggle="tab" href="#gift" role="tab">{{ __('admin.BookingGift') }}</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="trip-tab" data-bs-toggle="tab" href="#trip" role="tab">{{ __('admin.BookingTrip') }}</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="effective-tab" data-bs-toggle="tab" href="#effective" role="tab">{{ __('admin.BookingEffectivene') }}</a>
                </li>
            </ul>

            <div class="tab-content mt-3" id="bookingTabsContent">
                <div class="tab-pane fade show active" id="gift" role="tabpanel">
                    <table id="gift-table" class="table table-bordered w-100">
                        <thead>
                            <tr>
                                  <th>{{ __('orders.client') }}</th>
                        <th>{{ __('orders.vendor') }}</th>
                        <th>{{ __('orders.order_date') }}</th>
                        <th>{{ __('orders.cancel_date') }}</th>
                        <th>{{ __('orders.vendor_total') }}</th>
                        <th>{{ __('orders.customer_total') }}</th>
                        <th>{{ __('admin.admin_value') }}</th>

                        <th>{{ __('orders.status') }}</th>
                        
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane fade" id="trip" role="tabpanel">
                    <table id="trip-table" class="table table-bordered w-100">
                        <thead>
                            <tr>
                                  <th>{{ __('orders.client') }}</th>
                            <th>{{ __('orders.vendor') }}</th>
                            <th>{{ __('orders.order_date') }}</th>
                            <th>{{ __('orders.cancel_date') }}</th>
                            <th>{{ __('orders.vendor_total') }}</th>
                            <th>{{ __('orders.customer_total') }}</th>
                            <th>{{ __('admin.admin_value') }}</th>

                            <th>{{ __('orders.status') }}</th>
                            
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane fade" id="effective" role="tabpanel">
                    <table id="effective-table" class="table table-bordered w-100">
                        <thead>
                            <tr>
                                  <th>{{ __('orders.client') }}</th>
                        <th>{{ __('orders.vendor') }}</th>
                        <th>{{ __('orders.order_date') }}</th>
                        <th>{{ __('orders.cancel_date') }}</th>
                        <th>{{ __('orders.vendor_total') }}</th>
                        <th>{{ __('orders.customer_total') }}</th>
                        <th>{{ __('admin.admin_value') }}</th>

                        <th>{{ __('orders.status') }}</th>
                        
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            

        </div>
    </div>
</div>
@stop

@push('scripts')
<script>
    const statuses = @json($status); // Pass the status array from the controller


        let giftTable, tripTable, effectiveTable;

        function initGiftTable() {
            giftTable = $('#gift-table').DataTable({
                processing: true
                , serverSide: true
                , ajax: {
                    url: '{{ route("admin.orders.list") }}'
                    , data: {
                        type: 'BookingGift',
                        status:statuses

                    }
                }
                , columns: [     {data: 'client', name: 'client',orderable: false},
                {data: 'vendor', name: 'vendor',orderable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'cancel_date', name: 'cancel_date'},
                {data: 'total', name: 'total'},
                {data: 'customer_total', name: 'customer_total'},
                {data: 'admin_value', name: 'admin_value'},
                {data: 'status', name: 'status'},
                ]
            });
        }

        function initTripTable() {
            tripTable = $('#trip-table').DataTable({
                processing: true
                , serverSide: true
                , ajax: {
                    url: '{{ route("admin.orders.list") }}'
                    , data: {
                        type: 'BookingTrip',
                        status:statuses


                    }
                }
                , columns: [     {data: 'client', name: 'client',orderable: false},
                {data: 'vendor', name: 'vendor',orderable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'cancel_date', name: 'cancel_date'},
                {data: 'total', name: 'total'},
                {data: 'customer_total', name: 'customer_total'},
                {data: 'admin_value', name: 'admin_value'},
                {data: 'status', name: 'status'},
                ]
            });
        }

        function initEffectiveTable() {
            effectiveTable = $('#effective-table').DataTable({
                processing: true
                , serverSide: true
                , ajax: {
                    url: '{{ route("admin.orders.list") }}'
                    , data: {
                        type: 'BookingEffectivene',
                        status:statuses


                    }
                }
                , columns: [     {data: 'client', name: 'client',orderable: false},
                {data: 'vendor', name: 'vendor',orderable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'cancel_date', name: 'cancel_date'},
                {data: 'total', name: 'total'},
                {data: 'customer_total', name: 'customer_total'},
                {data: 'admin_value', name: 'admin_value'},
                {data: 'status', name: 'status'},
                ]
            });
        }

        // Handle tab show event
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            const target = $(e.target).attr('href');
            switch (target) {
                case '#gift':
                    if (!$.fn.dataTable.isDataTable('#gift-table')) {
                        initGiftTable();
                    } else {
                        giftTable.columns.adjust().draw();
                    }
                    break;
                case '#trip':
                    if (!$.fn.dataTable.isDataTable('#trip-table')) {
                        initTripTable();
                    } else {
                        tripTable.columns.adjust().draw();
                    }
                    break;
                case '#effective':
                    if (!$.fn.dataTable.isDataTable('#effective-table')) {
                        initEffectiveTable();
                    } else {
                        effectiveTable.columns.adjust().draw();
                    }
                    break;
            }
        });

        // Load first tab manually
        $(document).ready(function() {
            initGiftTable(); // initialize the default active tab
        });

    </script>





@endpush


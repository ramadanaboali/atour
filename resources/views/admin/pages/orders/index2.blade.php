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
                <table class="dt-multilingual table datatables-ajax">
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
                        <th>{{ __('orders.type') }}</th>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script>
        const statuses = @json($status); // Pass the status array from the controller

        var dt_ajax_table = $('.datatables-ajax');
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
                    d.name   = $('#filterForm #name').val();
                    d.status= statuses;
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                /*{data: 'DT_RowIndex', name: 'DT_RowIndex'},*/
                {data: 'client', name: 'client',orderable: false},
                {data: 'vendor', name: 'vendor',orderable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'cancel_date', name: 'cancel_date'},
                {data: 'total', name: 'total'},
                {data: 'customer_total', name: 'customer_total'},
                {data: 'admin_value', name: 'admin_value'},
                {data: 'status', name: 'status'},
                {data: 'source', name: 'source'},

            ],
            columnDefs: [


            ],
        });
        $('.btn_filter').click(function (){
            dt_ajax.DataTable().ajax.reload();
        });
    </script>
@endpush

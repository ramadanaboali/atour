@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('suppliers.plural') }}</title>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="box" class="font-medium-2"></i>
                        <span>{{ __('suppliers.plural') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
            <div class="mb-1 breadcrumb-right">
                <div class="dropdown">
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
                        <th>{{ __('orders.vendor') }}</th>
                        <th>{{ __('orders.type') }}</th>
                        <th>{{ __('orders.default.name') }}</th>
                        <th>{{ __('admin.other_fee') }}</th>
                        <th>{{ __('admin.payment_way_fee') }}</th>
                        <th>{{ __('admin.additional_tax') }}</th>
                        <th>{{ __('admin.suppliers_payments') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
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
            lengthMenu: [[10, 50, 100,500, -1], [10, 50, 100,500, "All"]],
            language: {
                paginate: {
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            ajax: {
                url: "{{ route('admin.suppliers.payments') }}",
                data: function (d) {
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                {data: 'vendor', name: 'vendor',orderable: false},
                {data: 'source', name: 'source'},
                {data: 'source_name', name: 'source_name'},
                {data: 'tax_value', name: 'tax_value'},
                {data: 'payment_way_value', name: 'payment_way_value'},
                {data: 'admin_value', name: 'admin_value'},
                {data: 'admin_fee_value', name: 'admin_fee_value'},
            ],
            columnDefs: [
            ],
        });
        $('.btn_filter').click(function (){
            dt_ajax.DataTable().ajax.reload();
        });

    </script>
@endpush

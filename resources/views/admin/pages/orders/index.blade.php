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
                        <span>{{ __('orders.plural') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
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
                        <th>{{ __('orders.address') }}</th>
                        <th>{{ __('orders.total') }}</th>
                        <th>{{ __('orders.members') }}</th>
                        <th>{{ __('orders.booking_date') }}</th>
                        @canany('orders.edit','orders.delete')
                            <th width="15%" class="text-center">{{ __('orders.options') }}</th>
                        @endcanany
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
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            ajax: {
                url: "{{ route('admin.orders.list') }}",
                data: function (d) {
                    d.name   = $('#filterForm #name').val();
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                /*{data: 'DT_RowIndex', name: 'DT_RowIndex'},*/
                {data: 'client', name: 'client',orderable: false},
                {data: 'vendor', name: 'vendor',orderable: false},
                {data: 'order_date', name: 'order_date'},
                {data: 'address', name: 'address'},
                {data: 'total', name: 'total'},
                {data: 'members', name: 'members'},
                {data: 'booking_date', name: 'booking_date'},
                @canany('orders.edit','orders.delete')
                {data: 'actions',name: 'actions',orderable: false,searchable: false},
                @endcanany
            ],
            columnDefs: [

                @canany('orders.edit','orders.delete')
                {
                    "targets": -1,
                    "render": function (data, type, row) {
                        var showUrl = '{{ route("admin.orders.show", ":id") }}';
                        showUrl = showUrl.replace(':id', row.id);

                        var deleteUrl = '{{ route("admin.orders.destroy", ":id") }}';
                        deleteUrl = deleteUrl.replace(':id', row.id);

                        return `
                               <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical" class="font-medium-2"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @can('orders.show')
                        <a class="dropdown-item" href="`+showUrl+`">
                                        <i data-feather="eye" class="font-medium-2"></i>
                                            <span>{{ __('orders.actions.show') }}</span>
                                        </a>
                                        @endcan
                        @can('orders.delete')
                        <a class="dropdown-item delete_item" data-url="`+deleteUrl+`" href="#">
                                            <i data-feather="trash" class="font-medium-2"></i>
                                             <span>{{ __('orders.actions.delete') }}</span>
                                        </a>
                                        @endcan
                        </div>
                   </div>
                    `;
                    }
                }
                @endcanany
            ],
        });
        $('.btn_filter').click(function (){
            dt_ajax.DataTable().ajax.reload();
        });
    </script>
@endpush

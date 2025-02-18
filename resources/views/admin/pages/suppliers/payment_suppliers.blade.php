@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('admin.accountants') }}</title>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="box" class="font-medium-2"></i>
                        <span>{{ __('admin.suppliers_accounts') }}</span>
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
                        <th>{{ __('admin.gifts') }}</th>
                        <th>{{ __('admin.trips') }}</th>
                        <th>{{ __('admin.effectivenes') }}</th>
                        <th>{{ __('admin.total_amount') }}</th>
                        <th>{{ __('admin.additional_tax') }}</th>
                        <th>{{ __('admin.payment_way_fee') }}</th>
                        <th>{{ __('admin.admin_percentage') }}</th>
                        <th>{{ __('admin.other_fee') }}</th>
                        <th>{{ __('admin.total_order_fee') }}</th>
                        <th>{{ __('admin.total_order_fee_setlment') }}</th>
                        <th>{{ __('admin.remain') }}</th>
                        @can('accounts.settlement')
                            
                        <th>{{ __('admin.options') }}</th>
                        @endcan
                    </tr>
                    </thead>
                    <tfoot align="right">
                        <tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>@can('accounts.settlement')<th></th>@endcan</tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
<div class="modal fade text-start" id="modalsettlement" tabindex="-1" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="settlementForm" method="post" action="#">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">{{ __('admin.dialogs.settlement.title') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __('admin.dialogs.settlement.info') }}
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-info">{{ __('admin.dialogs.settlement.confirm') }}</button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">{{ __('admin.dialogs.delete.cancel') }}</button>
                </div>
            </div>
        </form>
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
                url: "{{ route('admin.accounts.suppliers') }}",
                data: function (d) {
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'count_booking_gift', name: 'count_booking_gift'},
                {data: 'count_booking_trib', name: 'count_booking_trib'},
                {data: 'count_booking_effectivenes', name: 'count_booking_effectivenes'},
                {data: 'total_money', name: 'total_money'},
                {data: 'total_tax_value', name: 'total_tax_value'},
                {data: 'total_payment_way_value', name: 'total_payment_way_value'},
                {data: 'total_admin_value', name: 'total_admin_value'},
                {data: 'total_admin_fee_value', name: 'total_admin_fee_value'},
                {data: 'total_order_fees_0', name: 'total_order_fees_0'},
                {data: 'total_order_fees_1', name: 'total_order_fees_1'},
                {data: 'remain', name: 'remain'},
                @can('accounts.settlement')
                    
                {data: 'actions',name: 'actions',orderable: false,searchable: false},
                @endcan
            ],
              createdRow: function (row, data, dataIndex) {
                if (data.total_order_fees_0 > 0) {
                    $(row).addClass('table-danger');
                }
            },
            footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), data;
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                var col_1 = api.column( 1 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                var col_2 = api.column( 2 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                var col_3 = api.column( 3 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                var col_4 = api.column( 4 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                var col_5 = api.column( 5 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                var col_6 = api.column( 6 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                var col_7 = api.column( 7 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                var col_8 = api.column( 8 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                var col_9 = api.column( 9 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                var col_10 = api.column( 10 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                var col_11 = api.column( 11 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );               

                    $( api.column( 0 ).footer() ).html("{{ __('admin.total') }}");
                    $( api.column( 1 ).footer() ).html(col_1);
                    $( api.column( 2 ).footer() ).html(col_2);
                    $( api.column( 3 ).footer() ).html(col_3);
                    $( api.column( 4 ).footer() ).html(col_4);
                    $( api.column( 5 ).footer() ).html(col_5);
                    $( api.column( 6 ).footer() ).html(col_6);
                    $( api.column( 7 ).footer() ).html(col_7);
                    $( api.column( 8 ).footer() ).html(col_8);
                    $( api.column( 9 ).footer() ).html(col_9);
                    $( api.column( 10 ).footer() ).html(col_10);
                    $( api.column( 11 ).footer() ).html(col_11);
                },
            columnDefs: [
                 @canany('accounts.settlement')
                {
                     "targets": -1,
                    "render": function (data, type, row) {
                        var editUrl = '{{ route("admin.accounts.settlement", ":id") }}';
                        editUrl = editUrl.replace(':id', row.id);


                        return `
                               <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical" class="font-medium-2"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                 
                        @can('accounts.settlement')
                        <a class="dropdown-item settlement_item" data-url="`+editUrl+`" href="#">
                                            <i data-feather="dollar-sign" class="font-medium-2"></i>
                                             <span>{{ __('admin.do_settlement') }}</span>
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

         $('body').on('click', '.settlement_item', function (){
            var url= $(this).attr('data-url');
            $('#settlementForm').attr('action', url)
            $('#modalsettlement').modal('show')
            return false;
        });
    </script>
@endpush

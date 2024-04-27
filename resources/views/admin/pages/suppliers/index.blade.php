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
                        @can('suppliers.create')
                        <a class="btn btn-sm btn-outline-primary me-1 waves-effect" href="{{ route('admin.suppliers.create') }}">
                            <i data-feather="plus"></i>
                            <span class="active-sorting text-primary">{{ __('suppliers.actions.create') }}</span>
                        </a>
                        @endcan
                        @include('admin.pages.suppliers.filter')
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
                        <th>{{ __('suppliers.code') }}</th>
                        <th>{{ __('suppliers.image') }}</th>
                        <th>{{ __('suppliers.name') }}</th>
                        <th>{{ __('suppliers.email') }}</th>
                        <th>{{ __('suppliers.phone') }}</th>
                        <th>{{ __('suppliers.birthdate') }}</th>
                        <th>{{ __('suppliers.address') }}</th>
                        <th>{{ __('suppliers.active') }}</th>
                        <th>{{ __('suppliers.joining_date') }}</th>
                        <th>{{ __('suppliers.orders_count') }}</th>
                        @canany('suppliers.edit','suppliers.delete')
                            <th width="15%" class="text-center">{{ __('suppliers.options') }}</th>
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
                url: "{{ route('admin.suppliers.list') }}",
                data: function (d) {
                    d.name  = $('#filterForm #name').val();
                    d.email  = $('#filterForm #email').val();
                    d.phone  = $('#filterForm #phone').val();
                    d.birthdate  = $('#filterForm #birthdate').val();
                    d.city_id  = $('#filterForm #city_id').val();
                    d.joining_date  = $('#filterForm #joining_date').val();
                    d.active  = $('#filterForm #active').val();
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                /*{data: 'DT_RowIndex', name: 'DT_RowIndex'},*/
                {data: 'code', name: 'code'},
                {data: 'photo', name: 'photo'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'birthdate', name: 'birthdate'},
                {data: 'address', name: 'address'},
                {data: 'active', name: 'active'},
                {data: 'joining_date', name: 'joining_date'},
                {data: 'order_count', name: 'order_count',orderable: false,searchable: false},
                @canany('suppliers.edit','suppliers.delete')
                {data: 'actions',name: 'actions',orderable: false,searchable: false},
                @endcanany
            ],
            columnDefs: [

                @canany('suppliers.edit','suppliers.delete')
                {
                    "targets": -1,
                    "render": function (data, type, row) {
                        var editUrl = '{{ route("admin.suppliers.edit", ":id") }}';
                        editUrl = editUrl.replace(':id', row.id);

                        var deleteUrl = '{{ route("admin.suppliers.destroy", ":id") }}';
                        deleteUrl = deleteUrl.replace(':id', row.id);

                        return `
                               <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical" class="font-medium-2"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @can('suppliers.edit')
                        <a class="dropdown-item" href="`+editUrl+`">
                                        <i data-feather="edit-2" class="font-medium-2"></i>
                                            <span>{{ __('suppliers.actions.edit') }}</span>
                                        </a>
                                        @endcan
                        @can('suppliers.delete')
                        <a class="dropdown-item delete_item" data-url="`+deleteUrl+`" href="#">
                                            <i data-feather="trash" class="font-medium-2"></i>
                                             <span>{{ __('suppliers.actions.delete') }}</span>
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

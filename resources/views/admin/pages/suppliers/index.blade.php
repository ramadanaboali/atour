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
                        <th>{{ __('suppliers.image') }}</th>
                        <th>{{ __('suppliers.name') }}</th>
                        <th>{{ __('suppliers.email') }}</th>
                        <th>{{ __('suppliers.phone') }}</th>
                        <th>{{ __('suppliers.birthdate') }}</th>
                        <th>{{ __('suppliers.active') }}</th>
                        @canany('suppliers.edit','suppliers.delete')
                            <th width="15%" class="text-center">{{ __('suppliers.options') }}</th>
                        @endcanany
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" id="status" value="{{ $status }}">
    <input type="hidden" id="created_at" value="{{ $created_at }}">
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
                    d.type  = $('#filterForm #type').val();
                    d.city_id  = $('#filterForm #city_id').val();
                    d.active  = $('#filterForm #active').val();
                    d.status  = $('#status').val();
                    d.created_at  = $('#created_at').val();
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                /*{data: 'DT_RowIndex', name: 'DT_RowIndex'},*/
                {data: 'photo', name: 'photo'},
                {data: 'name', name: 'users.name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'birthdate', name: 'birthdate'},
                {data: 'active', name: 'active'},
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

                        var statusUrl = '{{ route("admin.suppliers.status", ":id") }}';
                        statusUrl = statusUrl.replace(':id', row.id);

                        return `
                               <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical" class="font-medium-2"></i>
                                    </button>
                                    <div class="dropdown-menu">

                        @can('suppliers.delete')
                        <a class="dropdown-item delete_item" data-url="`+deleteUrl+`" href="#">
                                            <i data-feather="trash" class="font-medium-2"></i>
                                             <span>{{ __('suppliers.actions.delete') }}</span>
                                        </a>
                                        @endcan
                                         @can('clients.status')
                        <a class="dropdown-item client_status" data-url="`+statusUrl+`" href="#">
                            <i data-feather="circle" class="font-medium-2"></i>
                                <span>{{ __('clients.actions.status') }}</span>
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

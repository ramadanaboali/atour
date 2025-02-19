@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('why_bookings.plural') }}</title>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="box" class="font-medium-2"></i>
                        <span>{{ __('why_bookings.plural') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
            <div class="mb-1 breadcrumb-right">
                @can('why_bookings.create')
                    <div class="dropdown">
                        <a class="btn btn-sm btn-outline-primary me-1 waves-effect" href="{{ route('admin.why_bookings.create') }}">
                            <i data-feather="plus"></i>
                            <span class="active-sorting text-primary">{{ __('why_bookings.actions.create') }}</span>
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-datatable">
                <table class="dt-multilingual table datatables-ajax">
                    <thead>
                    <tr>
                        <th>{{ __('why_bookings.default.name') }}</th>
                        <th>{{ __('why_bookings.description') }}</th>
                        <th>{{ __('why_bookings.image') }}</th>
                        <th>{{ __('why_bookings.active') }}</th>
                        <th>{{ __('why_bookings.created_at') }}</th>
                        @canany('why_bookings.edit','why_bookings.delete')
                            <th width="15%" class="text-center">{{ __('why_bookings.options') }}</th>
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
                url: "{{ route('admin.why_bookings.list') }}",
                data: function (d) {
                    d.name   = $('#filterForm #name').val();
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                /*{data: 'DT_RowIndex', name: 'DT_RowIndex'},*/
                {data: 'title', name: 'title',orderable: false},
                {data: 'description', name: 'description'},
                {data: 'photo', name: 'photo'},
                {data: 'active', name: 'active'},
                {data: 'created_at', name: 'created_at'},
                @canany('why_bookings.edit','why_bookings.delete')
                {data: 'actions',name: 'actions',orderable: false,searchable: false},
                @endcanany
            ],
            columnDefs: [

                @canany('why_bookings.edit','why_bookings.delete')
                {
                    "targets": -1,
                    "render": function (data, type, row) {
                        var editUrl = '{{ route("admin.why_bookings.edit", ":id") }}';
                        editUrl = editUrl.replace(':id', row.id);

                        var deleteUrl = '{{ route("admin.why_bookings.destroy", ":id") }}';
                        deleteUrl = deleteUrl.replace(':id', row.id);

                        return `
                               <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical" class="font-medium-2"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @can('why_bookings.edit')
                        <a class="dropdown-item" href="`+editUrl+`">
                                        <i data-feather="edit-2" class="font-medium-2"></i>
                                            <span>{{ __('why_bookings.actions.edit') }}</span>
                                        </a>
                                        @endcan
                        @can('why_bookings.delete')
                        <a class="dropdown-item delete_item" data-url="`+deleteUrl+`" href="#">
                                            <i data-feather="trash" class="font-medium-2"></i>
                                             <span>{{ __('why_bookings.actions.delete') }}</span>
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

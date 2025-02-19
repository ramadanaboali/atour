@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('adds.plural') }}</title>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="box" class="font-medium-2"></i>
                        <span>{{ __('adds.plural') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
            <div class="mb-1 breadcrumb-right">
                <div class="dropdown">
                        @can('adds.create')
                        <a class="btn btn-sm btn-outline-primary me-1 waves-effect" href="{{ route('admin.adds.create') }}">
                            <i data-feather="plus"></i>
                            <span class="active-sorting text-primary">{{ __('adds.actions.create') }}</span>
                        </a>
                        @endcan
                    </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-datatable">
                <table class="dt-multilingual table datatables-ajax">
                    <thead>
                    <tr>
                        <th>{{ __('adds.default.name') }}</th>
                        <th>{{ __('adds.date') }}</th>
                        <th>{{ __('adds.description') }}</th>
                        <th>{{ __('adds.views') }}</th>
                        <th>{{ __('adds.location') }}</th>
                        <th>{{ __('adds.active') }}</th>
                        <th>{{ __('adds.image') }}</th>
                        @canany('adds.edit','adds.delete')
                            <th width="15%" class="text-center">{{ __('adds.options') }}</th>
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
                url: "{{ route('admin.adds.list') }}",
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
                {data: 'date', name: 'start_date'},
                {data: 'description', name: 'description',orderable: false},
                {data: 'views', name: 'views'},
                {data: 'location', name: 'location'},
                {data: 'active', name: 'active'},
                {data: 'photo', name: 'photo'},
                @canany('adds.edit','adds.delete')
                {data: 'actions',name: 'actions',orderable: false,searchable: false},
                @endcanany
            ],
            columnDefs: [

                @canany('adds.edit','adds.delete')
                {
                    "targets": -1,
                    "render": function (data, type, row) {
                        var editUrl = '{{ route("admin.adds.edit", ":id") }}';
                        editUrl = editUrl.replace(':id', row.id);

                        var deleteUrl = '{{ route("admin.adds.destroy", ":id") }}';
                        deleteUrl = deleteUrl.replace(':id', row.id);

                        return `
                               <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical" class="font-medium-2"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @can('adds.edit')
                        <a class="dropdown-item" href="`+editUrl+`">
                                        <i data-feather="edit-2" class="font-medium-2"></i>
                                            <span>{{ __('adds.actions.edit') }}</span>
                                        </a>
                                        @endcan
                        @can('adds.delete')
                        <a class="dropdown-item delete_item" data-url="`+deleteUrl+`" href="#">
                                            <i data-feather="trash" class="font-medium-2"></i>
                                             <span>{{ __('adds.actions.delete') }}</span>
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

@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('features.plural') }}</title>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="box" class="font-medium-2"></i>
                        <span>{{ __('features.plural') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
            <div class="mb-1 breadcrumb-right">
                @can('features.create')
                    <div class="dropdown">
                        <a class="btn btn-sm btn-outline-primary me-1 waves-effect" href="{{ route('admin.features.create') }}">
                            <i data-feather="plus"></i>
                            <span class="active-sorting text-primary">{{ __('features.actions.create') }}</span>
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
                        <th>{{ __('features.default.name') }}</th>
                        <th>{{ __('features.photo') }}</th>
                        @canany('features.edit','features.delete')
                            <th width="15%" class="text-center">{{ __('features.options') }}</th>
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
                url: "{{ route('admin.features.list') }}",
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
                {data: 'photo', name: 'photo'},
                @canany('features.edit','features.delete')
                {data: 'actions',name: 'actions',orderable: false,searchable: false},
                @endcanany
            ],
            columnDefs: [

                @canany('features.edit','features.delete')
                {
                    "targets": -1,
                    "render": function (data, type, row) {
                        var editUrl = '{{ route("admin.features.edit", ":id") }}';
                        editUrl = editUrl.replace(':id', row.id);

                        var deleteUrl = '{{ route("admin.features.destroy", ":id") }}';
                        deleteUrl = deleteUrl.replace(':id', row.id);

                        return `
                               <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical" class="font-medium-2"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @can('features.edit')
                        <a class="dropdown-item" href="`+editUrl+`">
                                        <i data-feather="edit-2" class="font-medium-2"></i>
                                            <span>{{ __('features.actions.edit') }}</span>
                                        </a>
                                        @endcan
                        @can('features.delete')
                        <a class="dropdown-item delete_item" data-url="`+deleteUrl+`" href="#">
                                            <i data-feather="trash" class="font-medium-2"></i>
                                             <span>{{ __('features.actions.delete') }}</span>
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

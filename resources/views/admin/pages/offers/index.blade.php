@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('offers.plural') }}</title>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="box" class="font-medium-2"></i>
                        <span>{{ __('offers.plural') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
            <div class="mb-1 breadcrumb-right">
                <div class="dropdown">
                        @can('offers.create')
                        <a class="btn btn-sm btn-outline-primary me-1 waves-effect" href="{{ route('admin.offers.create') }}">
                            <i data-feather="plus"></i>
                            <span class="active-sorting text-primary">{{ __('offers.actions.create') }}</span>
                        </a>
                        @endcan
                        {{-- @include('admin.pages.offers.filter') --}}
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
                        {{-- <th>{{ __('offers.supplier_name') }}</th>
                        <th>{{ __('offers.supplier_email') }}</th>
                        <th>{{ __('offers.supplier_phone') }}</th> --}}
                        <th>{{ __('offers.title') }}</th>
                        <th>{{ __('offers.url') }}</th>
                        <th>{{ __('offers.active') }}</th>
                        @canany('offers.edit','offers.delete')
                            <th width="15%" class="text-center">{{ __('offers.options') }}</th>
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
                url: "{{ route('admin.offers.list') }}",
                data: function (d) {
                    d.name   = $('#filterForm #name').val();
                    d.email= $('#filterForm #email').val();
                    d.phone= $('#filterForm #phone').val();
                    d.city_id= $('#filterForm #city_id').val();
                    d.active= $('#filterForm #active').val();
                }
            },
            drawCallback: function (settings) {
                feather.replace();
            },
            columns: [
                /*{data: 'DT_RowIndex', name: 'DT_RowIndex'},*/
                // {data: 'supplier_name', name: 'supplier_name'},
                // {data: 'supplier_email', name: 'supplier_email'},
                // {data: 'supplier_phone', name: 'supplier_phone'},
                {data: 'title', name: 'title'},
                {data: 'url', name: 'url'},
                {data: 'active', name: 'active'},
                @canany('offers.edit','offers.delete')
                {data: 'actions',name: 'actions',orderable: false,searchable: false},
                @endcanany
            ],
            columnDefs: [

                @canany('offers.edit','offers.delete')
                {
                    "targets": -1,
                    "render": function (data, type, row) {
                        var editUrl = '{{ route("admin.offers.edit", ":id") }}';
                        editUrl = editUrl.replace(':id', row.id);

                        var deleteUrl = '{{ route("admin.offers.destroy", ":id") }}';
                        deleteUrl = deleteUrl.replace(':id', row.id);

                        return `
                               <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical" class="font-medium-2"></i>
                                    </button>
                                    <div class="dropdown-menu">

                                        @can('offers.edit')
                        <a class="dropdown-item" href="`+editUrl+`">
                                        <i data-feather="edit-2" class="font-medium-2"></i>
                                            <span>{{ __('offers.actions.edit') }}</span>
                                        </a>
                                        @endcan
                        @can('offers.delete')
                        <a class="dropdown-item delete_item" data-url="`+deleteUrl+`" href="#">
                                            <i data-feather="trash" class="font-medium-2"></i>
                                             <span>{{ __('offers.actions.delete') }}</span>
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
    $(window).on('load', function() {
        $('body').on('click', '.active_offer', function (){

           var url= $(this).attr('data-url');
              $('#StatusForm').attr('action', url)
            $('#ClientStatus').modal('show')
            return false;
        });
    });
    </script>
@endpush

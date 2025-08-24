@extends('admin.layouts.master')
@section('title')
<title>{{ config('app.name') }} | {{ __('contacts.plural') }}</title>
@endsection
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h1 class="bold mb-0 mt-1 text-dark">
                    <i data-feather="box" class="font-medium-2"></i>
                    <span>{{ __('contacts.plural') }}</span>
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
        <div class="card-datatable table-responsive">

            <table class="dt-multilingual table datatables-ajax">
                <thead>
                    <tr>
                        <th>{{ __('contacts.code') }}</th>
                        <th>{{ __('contacts.name') }}</th>
                        <th>{{ __('contacts.email') }}</th>
                        <th>{{ __('admin.phone') }}</th>
                        <th>{{ __('contacts.title') }}</th>
                        <th>{{ __('contacts.status') }}</th>
                        <th>{{ __('contacts.closed_at') }}</th>
                        <th>{{ __('contacts.notes') }}</th>
                        <th>{{ __('contacts.created_at') }}</th>
                        <th>{{ __('contacts.description') }}</th>
                        @canany('contacts.delete')
                        <th width="15%" class="text-center">{{ __('contacts.options') }}</th>
                        @endcanany
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade text-start" id="ClientStatus2" tabindex="-1" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="StatusForm2" method="post" action="#">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">{{ __('admin.dialogs.client_status.title') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- {{ __('admin.dialogs.client_status.info') }} --}}
                    <div class="row">
                        <div class="col-md-12">
                            <label for="status">{{ __('contacts.status') }}</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">{{ __('admin.select') }}</option>
                                <option value="onprogress">{{ __('contacts.onprogress') }}</option>
                                <option value="closed">{{ __('contacts.close') }}</option>

                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">

                            {{-- <div class="form-group"> --}}
                            <label for="notes">{{ __('admin.notes') }}</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success">{{ __('admin.dialogs.client_status.confirm') }}</button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">{{ __('admin.dialogs.client_status.cancel') }}</button>
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
        processing: true
        , serverSide: true
        , searching: true
        , paging: true
        , info: false
        , lengthMenu: [
            [10, 50, 100, 500, -1]
            , [10, 50, 100, 500, "All"]
        ]
        , language: {
            paginate: {
                // remove previous & next text from pagination
                previous: '&nbsp;'
                , next: '&nbsp;'
            }
        }
        , ajax: {
            url: "{{ route('admin.contacts.list') }}"
            , data: function(d) {
                d.name = $('#filterForm #name').val();
            }
        }
        , drawCallback: function(settings) {
            feather.replace();
        }
        , columns: [
            /*{data: 'DT_RowIndex', name: 'DT_RowIndex'},*/
            {
                data: 'id'
                , name: 'id'
            }
            , {
                data: 'name'
                , name: 'name'
            }
            , {
                data: 'email'
                , name: 'email'
            }
            , {
                data: 'phone'
                , name: 'phone'
            }
            , {
                data: 'title'
                , name: 'title'
            }
            , {
                data: 'status'
                , name: 'status'
            }
            , {
                data: 'closed_at'
                , name: 'closed_at'
            }
            , {
                data: 'notes'
                , name: 'notes'
            }
            , {
                data: 'created_at'
                , name: 'created_at'
            }
            , {
                data: 'description'
                , name: 'description'
            }
            , @canany('contacts.delete') {
                data: 'actions'
                , name: 'actions'
                , orderable: false
                , searchable: false
            }
            , @endcanany
        ]
        , columnDefs: [

            @canany('contacts.delete') {
                "targets": -1
                , "render": function(data, type, row) {
                    var deleteUrl = '{{ route("admin.contacts.destroy", ":id") }}';
                    deleteUrl = deleteUrl.replace(':id', row.id);

                    return `
                               <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical" class="font-medium-2"></i>
                                    </button>
                                    <div class="dropdown-menu">
                        @can('contacts.delete')
                        <a class="dropdown-item delete_item" data-url="` + deleteUrl + `" href="#">
                                            <i data-feather="trash" class="font-medium-2"></i>
                                             <span>{{ __('contacts.actions.delete') }}</span>
                                        </a>
                                        @endcan
                        </div>
                   </div>
                    `;
                }
            }
            @endcanany
        ]
    , });
    $('.btn_filter').click(function() {
        dt_ajax.DataTable().ajax.reload();
    });
    $('body').on('click', '.change_status', function() {
        var url = $(this).attr('data-url');
        // alert('Change status clicked'+url);
        $('#StatusForm2').attr('action', url)

        $('#ClientStatus2').modal('show')

        return false;
    });

</script>
@endpush

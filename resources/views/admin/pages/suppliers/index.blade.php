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
    <div class="modal fade text-start" id="SettingModal" tabindex="-1" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="SettingForm" method="get" action="#">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">{{ __('admin.dialogs.client_status.title') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="row">
                    <div class="mb-1 col-md-6  @error('can_pay_later') is-invalid @enderror">
                        <br>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="can_pay_later"
                                    value="1" id="can_pay_later"/>
                            <label class="form-check-label" for="can_pay_later">{{ __('suppliers.can_pay_later') }}</label>
                        </div>
                    </div>
                    <div class="mb-1 col-md-6  @error('can_cancel') is-invalid @enderror">
                        <br>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="can_cancel"
                                    value="1" id="can_cancel"/>
                            <label class="form-check-label" for="can_cancel">{{ __('suppliers.can_cancel') }}</label>
                        </div>
                    </div>
                    <div class="mb-1 col-md-6  @error('pay_on_deliver') is-invalid @enderror">
                        <br>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="pay_on_deliver"
                                    value="1" id="pay_on_deliver"/>
                            <label class="form-check-label" for="pay_on_deliver">{{ __('suppliers.pay_on_deliver') }}</label>
                        </div>
                    </div>
                    <div class="mb-1 col-md-6  @error('ban_vendor') is-invalid @enderror">
                        <br>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="ban_vendor"
                                    value="1" id="ban_vendor"/>
                            <label class="form-check-label" for="ban_vendor">{{ __('suppliers.ban_vendor') }}</label>
                        </div>
                    </div>
                </div>
                <br>
                <hr>
                <p>{{ __('admin.admin_percentage') }}</p>
                <div class="row">
                    <div class="col-md-6">
                        <label for="">{{ __('suppliers.type') }}</label>
                        <select name="admin_value_type" id="admin_value_type" class="form-select">
                            <option value="percentage">{{ __('suppliers.percentage') }}</option>
                            <option value="const">{{ __('suppliers.const') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="">{{ __('suppliers.value') }}</label>
                            <input type="number" id="admin_value" name="admin_value" class="form-control">
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

                         var showUrl = '{{ route("admin.suppliers.show", ":id") }}';
                        showUrl = showUrl.replace(':id', row.id);

                        var deleteUrl = '{{ route("admin.suppliers.destroy", ":id") }}';
                        deleteUrl = deleteUrl.replace(':id', row.id);

                        var statusUrl = '{{ route("admin.suppliers.status", ":id") }}';
                        statusUrl = statusUrl.replace(':id', row.id);

                        var settingUrl = '{{ route("admin.suppliers.setting", ":id") }}';
                        settingUrl = settingUrl.replace(':id', row.id);

                        return `
                               <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical" class="font-medium-2"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @can('suppliers.show')
                                                    <a class="dropdown-item" href="`+showUrl+`">
                                        <i data-feather="eye" class="font-medium-2"></i>
                                            <span>{{ __('suppliers.actions.show') }}</span>
                                        </a>
                                        @endcan
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
                                         @can('clients.status')
                        <a class="dropdown-item "  href="`+settingUrl+`" >
                            <i data-feather="settings" class="font-medium-2"></i>
                                <span>{{ __('suppliers.actions.settings') }}</span>
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

            $('body').on('click', '.vendor_setting', function (){
                    var url= $(this).attr('data-url');
                    var can_pay_later= $(this).attr('data-can_pay_later');
                    var admin_value_type= $(this).attr('data-admin_value_type');
                    var admin_value= $(this).attr('data-admin_value');
                    document.getElementById('admin_value').value = admin_value;
                    document.getElementById('admin_value_type').value = admin_value_type;
                    if(can_pay_later==1){
                        document.getElementById('can_pay_later').checked = true;
                    }else{
                        document.getElementById('can_pay_later').checked = false;
                    }
                    var can_cancel= $(this).attr('data-can_cancel');
                    if(can_cancel==1){
                        document.getElementById('can_cancel').checked = true;
                    }else{
                        document.getElementById('can_cancel').checked = false;
                    }
                    var pay_on_deliver= $(this).attr('data-pay_on_deliver');
                    if(pay_on_deliver==1){
                        document.getElementById('pay_on_deliver').checked = true;
                    }else{
                        document.getElementById('pay_on_deliver').checked = false;
                    }
                    var ban_vendor= $(this).attr('data-ban_vendor');
                    if(ban_vendor==1){
                        document.getElementById('ban_vendor').checked = true;
                    }else{
                        document.getElementById('ban_vendor').checked = false;
                    }
                    $('#SettingForm').attr('action', url)
                $('#SettingModal').modal('show')
                return false;
            });
        });
    </script>
@endpush

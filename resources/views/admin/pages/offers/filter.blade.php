<button type="button" class="btn btn-sm btn-outline-primary bg-white me-1 waves-effect border-0" type="button" data-bs-toggle="modal" data-bs-target="#filterModal">
    <i data-feather='zoom-in'></i>
    <span class="active-sorting text-primary">{{ __('clients.advanced_filter') }}</span>
    <i data-feather='chevron-left'></i>
</button>
<div class="modal modal-slide-in fade" id="filterModal">
    <div class="modal-dialog sidebar-sm">
        <div class="add-new-record modal-content pt-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('clients.advanced_filter') }}</h5>
            </div>
            <div class="modal-body flex-grow-1 text-start">
                <form id="filterForm" class="">
                    <div class="row">
                        <div class="mb-1 col-md-12"  >
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">{{ __('clients.name') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" id="name" class="form-control"  value="">
                                </div>
                            </div>
                        </div>
                        <div class="mb-1 col-md-12"  >
                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">{{ __('clients.email') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" type="email" id="email" class="form-control"  value="">
                                </div>
                            </div>
                        </div>
                        <div class="mb-1 col-md-12"  >
                            <div class="form-group row">
                                <label for="phone" class="col-sm-2 col-form-label">{{ __('clients.phone') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" type="number" id="phone" class="form-control"  value="">
                                </div>
                            </div>
                        </div>


                         <div class="mb-1 col-md-12"  >
                            <div class="form-group row">
                                <label for=" active" class="col-sm-2 col-form-label">{{ __('clients.active') }}</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="active">
                                        <option value="">{{ __('admin.select') }}</option>
                                        <option value="1">{{ __('clients.active') }}</option>
                                        <option value="0">{{ __('clients.notactive') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                       

                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn_filter">{{ __('clients.search') }}</button>
                            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">
                                {{ __('clients.cancel') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


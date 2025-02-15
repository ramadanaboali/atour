@extends('admin.layouts.master')

@section('title')
<title>{{ config('app.name') }} | {{ __('suppliers.plural') }}</title>
@endsection
@section('content')

<form id="SettingForm" method="post" action="{{ route('admin.suppliers.saveSetting',['id'=>$item->id]) }}">
    @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{  __('suppliers.actions.settings')  }}</span>
                        </h1>
                    </div>
                </div>
            </div>
          <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('sliders.actions.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-6  @error('can_pay_later') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="can_pay_later"
                                        value="1" id="can_pay_later"  @checked($item->can_pay_later==1) />
                                <label class="form-check-label" for="can_pay_later">{{ __('suppliers.can_pay_later') }}</label>
                            </div>
                        </div>
                        <div class="mb-1 col-md-6  @error('can_cancel') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="can_cancel"
                                        value="1" id="can_cancel"   @checked($item->can_cancel==1)/>
                                <label class="form-check-label" for="can_cancel">{{ __('suppliers.can_cancel') }}</label>
                            </div>
                        </div>
                        <div class="mb-1 col-md-6  @error('pay_on_deliver') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="pay_on_deliver"
                                        value="1" id="pay_on_deliver"   @checked($item->pay_on_deliver==1)/>
                                <label class="form-check-label" for="pay_on_deliver">{{ __('suppliers.pay_on_deliver') }}</label>
                            </div>
                        </div>
                        <div class="mb-1 col-md-6  @error('ban_vendor') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="ban_vendor"
                                        value="1" id="ban_vendor"   @checked($item->ban_vendor==1) />
                                <label class="form-check-label" for="ban_vendor">{{ __('suppliers.ban_vendor') }}</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <h3>{{ __('admin.additional_tax') }}</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">{{ __('suppliers.type') }}</label>
                            <select name="tax_type" id="tax_type" class="form-select">
                                <option value="percentage" {{ ($user_fee->tax_value??null)=='percentage'?'selected':'' }}>{{ __('suppliers.percentage') }}</option>
                                <option value="const" {{ ($user_fee->tax_value??null)=='const'?'selected':'' }}>{{ __('suppliers.const') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">{{ __('suppliers.value') }}</label>
                                <input type="number" id="tax_value" name="tax_value" class="form-control" value="{{ $user_fee->tax_value ?? old('tax_value') }}">
                        </div>
                    </div>
                    <br>
                    <hr>
                    <h3>{{ __('admin.payment_way_fee') }}</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">{{ __('suppliers.type') }}</label>
                            <select name="payment_way_type" id="payment_way_type" class="form-select">
                                <option value="percentage"  {{ ($user_fee->payment_way_type??null)=='percentage'?'selected':'' }}>{{ __('suppliers.percentage') }}</option>
                                <option value="const" {{ ($user_fee->payment_way_type??null)=='const'?'selected':'' }}>{{ __('suppliers.const') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">{{ __('suppliers.value') }}</label>
                                <input type="number" id="payment_way_value" name="payment_way_value" class="form-control"  value="{{ $user_fee->payment_way_value ?? old('payment_way_value') }}">
                        </div>
                    </div>
                    <br>
                    <hr>
                    <h3>{{ __('admin.admin_percentage') }}</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">{{ __('suppliers.type') }}</label>
                            <select name="admin_type" id="admin_type" class="form-select">
                                <option value="percentage" {{ ($user_fee->admin_type??null)=='percentage'?'selected':'' }}>{{ __('suppliers.percentage') }}</option>
                                <option value="const" {{ ($user_fee->admin_type??null)=='const'?'selected':'' }}>{{ __('suppliers.const') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">{{ __('suppliers.value') }}</label>
                                <input type="number" id="admin_value" name="admin_value" class="form-control" value="{{ $user_fee->admin_value ?? old('admin_value') }}">
                        </div>
                    </div>
                    <br>
                    <hr>
                    <h3>{{ __('admin.other_fee') }}</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">{{ __('suppliers.type') }}</label>
                            <select name="admin_fee_type" id="admin_fee_type" class="form-select">
                                <option value="percentage" {{ ($user_fee->admin_fee_type??null)=='percentage'?'selected':'' }}>{{ __('suppliers.percentage') }}</option>
                                <option value="const" {{ ($user_fee->admin_fee_type??null)=='const'?'selected':'' }}>{{ __('suppliers.const') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">{{ __('suppliers.value') }}</label>
                                <input type="number" id="admin_fee_value" name="admin_fee_value" class="form-control" value="{{ $user_fee->admin_fee_value ?? old('admin_fee_value') }}">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </form>
@stop

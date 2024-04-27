@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('suppliers.plural') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.suppliers.update', $item->id) : route('admin.suppliers.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('suppliers.actions.edit') : __('suppliers.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('suppliers.actions.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-4  @error('name') is-invalid @enderror">
                            <label class="form-label" for="name">{{ __('admin.name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder=""
                                   value="{{ $item->name ?? old('name') }}" required/>
                            @error('name')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4  @error('code') is-invalid @enderror">
                            <label class="form-label" for="code">{{ __('suppliers.code') }}</label>
                            <input type="text" name="code" id="code" class="form-control" placeholder=""
                                   value="{{ $item->code ?? old('code') }}" required/>
                            @error('code')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('email') is-invalid @enderror">
                            <label class="form-label" for="email">{{ __('admin.email') }}</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder=""
                                   value="{{ $item->email ?? old('email') }}" required/>
                            @error('email')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('phone') is-invalid @enderror">
                            <label class="form-label" for="phone">{{ __('admin.phone') }}</label>
                            <input type="number" name="phone" id="phone" class="form-control" placeholder=""
                                   value="{{ $item->phone ?? old('phone') }}" required/>
                            @error('phone')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('address') is-invalid @enderror">
                            <label class="form-label" for="address">{{ __('admin.address') }}</label>
                            <input type="text" name="address" id="address" class="form-control" placeholder=""
                                   value="{{ $item->address ?? old('address') }}" />
                            @error('address')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('nationality') is-invalid @enderror">
                            <label class="form-label" for="nationality">{{ __('suppliers.nationality') }}</label>
                            <input type="text" name="nationality" id="nationality" class="form-control" placeholder=""
                                   value="{{ $item->nationality ?? old('nationality') }}" />
                            @error('nationality')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('birthdate') is-invalid @enderror">
                            <label class="form-label" for="birthdate">{{ __('suppliers.birthdate') }}</label>
                            <input type="text" name="birthdate" id="birthdate" class="form-control flatpickr-basic" placeholder=""
                                   value="{{ $item->birthdate ?? old('birthdate') }}" />
                            @error('birthdate')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('joining_date_from') is-invalid @enderror">
                            <label class="form-label" for="joining_date_from">{{ __('suppliers.joining_date_from') }}</label>
                            <input type="text" name="joining_date_from" id="joining_date_from" class="form-control flatpickr-basic" placeholder=""
                                   value="{{ $item->joining_date_from ?? old('joining_date_from') }}" />
                            @error('joining_date_from')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('joining_date_to') is-invalid @enderror">
                            <label class="form-label" for="joining_date_to">{{ __('suppliers.joining_date_to') }}</label>
                            <input type="text" name="joining_date_to" id="joining_date_to" class="form-control flatpickr-basic" placeholder=""
                                   value="{{ $item->joining_date_to ?? old('joining_date_to') }}" />
                            @error('joining_date_to')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('city_id') is-invalid @enderror">
                            <label class="form-label" for="city_id">{{ __('suppliers.city') }}</label>
                            <select name="city_id" id="city_id" class="form-control ajax_select2 extra_field"
                                    data-ajax--url="{{ route('admin.cities.select') }}"
                                    data-ajax--cache="true">
                                @isset($item->city)
                                    <option value="{{ $item->city->id }}" selected>{{ $item->city->title }}</option>
                                @endisset
                            </select>
                            @error('city_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('password') is-invalid @enderror">
                            <label class="form-label">{{ __('users.password') }}</label>
                            <input class="form-control input" name="password"  placeholder="" type="password"
                                   autocomplete="false" readonly onfocus="this.removeAttribute('readonly');">
                            @error('password')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('password_confirmation') is-invalid @enderror">
                            <label class="form-label">{{ __('users.password_confirmation') }}</label>
                            <input class="form-control input" name="password_confirmation"  placeholder="" type="password"
                                   autocomplete="false" readonly onfocus="this.removeAttribute('readonly');">
                            @error('password_confirmation')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-2  @error('active') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active"
                                        value="1" id="active"
                                        @checked($item->active ?? false )/>
                                <label class="form-check-label" for="active">{{ __('suppliers.active') }}</label>
                            </div>
                            @error('active')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('image') is-invalid @enderror">
                            <label class="form-label" for="image">{{ __('suppliers.file') }}</label>
                            <input type="file" class="form-control input" name="image" id="image">
                            @error('image')
                            <span class="error">{{ $message }}</span>
                            @enderror
                            <div>
                                <br>
                                @if(isset($item) && !empty($item->image))
                                    <img src="{{ $item->photo }}"
                                         class="img-fluid img-thumbnail">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

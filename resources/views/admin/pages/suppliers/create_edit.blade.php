@extends('admin.layouts.master')

@section('title')
<title>{{ config('app.name') }} | {{ __('suppliers.plural') }}</title>
@endsection
@section('content')

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
    <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
        <div class="mb-1 breadcrumb-right">
            <div class="dropdown">

            </div>
        </div>
    </div>
</div>
<div class="content-body">


    <form action="{{ isset($item) ? route('admin.suppliers.update', $item->id) : route('admin.suppliers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($item))
            @method('PUT')
        @endif
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    {{ isset($item) ? __('suppliers.actions.edit') : __('suppliers.actions.create') }}
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    
                    <div class="mb-1 col-md-4  @error('name') is-invalid @enderror">
                        <label class="form-label" for="name">{{ __('admin.name') }}</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="" value="{{ $item->name ?? old('name') }}" required />
                        @error('name')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-1 col-md-4  @error('code') is-invalid @enderror">
                        <label class="form-label" for="code">{{ __('suppliers.code') }}</label>
                        <input type="text" name="code" id="code" class="form-control" placeholder="" value="{{ $item->code ?? old('code') }}" required />
                        @error('code')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-1 col-md-4  @error('email') is-invalid @enderror">
                        <label class="form-label" for="email">{{ __('admin.email') }}</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="" value="{{ $item->email ?? old('email') }}" required />
                        @error('email')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-1 col-md-4  @error('phone') is-invalid @enderror">
                        <label class="form-label" for="phone">{{ __('admin.phone') }}</label>
                        <input type="number" name="phone" id="phone" class="form-control" placeholder="" value="{{ $item->phone ?? old('phone') }}" required />
                        @error('phone')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-1 col-md-4  @error('address') is-invalid @enderror">
                        <label class="form-label" for="address">{{ __('admin.address') }}</label>
                        <input type="text" name="address" id="address" class="form-control" placeholder="" value="{{ $item->address ?? old('address') }}" />
                        @error('address')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-1 col-md-4  @error('nationality') is-invalid @enderror">
                        <label class="form-label" for="nationality">{{ __('suppliers.nationality') }}</label>
                        <input type="text" name="nationality" id="nationality" class="form-control" placeholder="" value="{{ $item->nationality ?? old('nationality') }}" />
                        @error('nationality')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-1 col-md-4  @error('birthdate') is-invalid @enderror">
                        <label class="form-label" for="birthdate">{{ __('suppliers.birthdate') }}</label>
                        <input type="text" name="birthdate" id="birthdate" class="form-control flatpickr-basic" placeholder="" value="{{ $item->birthdate ?? old('birthdate') }}" />
                        @error('birthdate')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                   
                    <div class="mb-1 col-md-4  @error('city_id') is-invalid @enderror">
                        <label class="form-label" for="city_id">{{ __('suppliers.city') }}</label>
                        <select name="city_id" id="city_id" class="form-control ajax_select2 extra_field" data-ajax--url="{{ route('admin.cities.select') }}" data-ajax--cache="true">
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
                        <input class="form-control input" name="password" placeholder="" type="password" autocomplete="false" readonly onfocus="this.removeAttribute('readonly');">
                        @error('password')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-1 col-md-4  @error('password_confirmation') is-invalid @enderror">
                        <label class="form-label">{{ __('users.password_confirmation') }}</label>
                        <input class="form-control input" name="password_confirmation" placeholder="" type="password" autocomplete="false" readonly onfocus="this.removeAttribute('readonly');">
                        @error('password_confirmation')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-1 col-md-2  @error('active') is-invalid @enderror">
                        <br>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="active" value="1" id="active" @checked($item->active ?? false )/>
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
                            <img src="{{ $item->photo }}" class="img-fluid img-thumbnail">
                            @endif
                        </div>
                    </div>
                    {{-- ...existing code... --}}

                    <div class="mb-1 col-md-4 @error('tour_guid') is-invalid @enderror">
                        <label class="form-label" for="tour_guid">{{ __('suppliers.tour_guid') }}</label>
                        <input type="text" name="tour_guid" id="tour_guid" class="form-control" value="{{ $item->tour_guid ?? old('tour_guid') }}">
                        @error('tour_guid')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('rerequest_reason') is-invalid @enderror">
                        <label class="form-label" for="rerequest_reason">{{ __('suppliers.rerequest_reason') }}</label>
                        <input type="text" name="rerequest_reason" id="rerequest_reason" class="form-control" value="{{ $item->rerequest_reason ?? old('rerequest_reason') }}">
                        @error('rerequest_reason')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('licence_image') is-invalid @enderror">
                        <label class="form-label" for="licence_image">{{ __('suppliers.licence_image') }}</label>
                        <input type="file" name="licence_image" id="licence_image" class="form-control">
                        @if(isset($item) && !empty($item->licence_image))
                        <img src="{{ $item->licence_image_url }}" class="img-fluid img-thumbnail mt-1">
                        @endif
                        @error('licence_image')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('profile') is-invalid @enderror">
                        <label class="form-label" for="profile">{{ __('suppliers.profile') }}</label>
                        <input type="text" name="profile" id="profile" class="form-control" value="{{ $item->profile ?? old('profile') }}">
                        @error('profile')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('type') is-invalid @enderror">
                        <label class="form-label" for="type">{{ __('suppliers.type') }}</label>
                        <input type="text" name="type" id="type" class="form-control" value="{{ $item->type ?? old('type') }}">
                        @error('type')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('country_id') is-invalid @enderror">
                        <label class="form-label" for="country_id">{{ __('suppliers.country') }}</label>
                        <select name="country_id" id="country_id" class="form-control ajax_select2" data-ajax--url="{{ route('admin.countries.select') }}">
                            @isset($item->country)
                            <option value="{{ $item->country->id }}" selected>{{ $item->country->title }}</option>
                            @endisset
                        </select>
                        @error('country_id')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('streat') is-invalid @enderror">
                        <label class="form-label" for="streat">{{ __('suppliers.streat') }}</label>
                        <input type="text" name="streat" id="streat" class="form-control" value="{{ $item->streat ?? old('streat') }}">
                        @error('streat')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('postal_code') is-invalid @enderror">
                        <label class="form-label" for="postal_code">{{ __('suppliers.postal_code') }}</label>
                        <input type="text" name="postal_code" id="postal_code" class="form-control" value="{{ $item->postal_code ?? old('postal_code') }}">
                        @error('postal_code')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('national_id') is-invalid @enderror">
                        <label class="form-label" for="national_id">{{ __('suppliers.national_id') }}</label>
                        <input type="text" name="national_id" id="national_id" class="form-control" value="{{ $item->national_id ?? old('national_id') }}">
                        @error('national_id')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('user_id') is-invalid @enderror">
                        <label class="form-label" for="user_id">{{ __('suppliers.user_id') }}</label>
                        <input type="text" name="user_id" id="user_id" class="form-control" value="{{ $item->user_id ?? old('user_id') }}">
                        @error('user_id')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('description') is-invalid @enderror">
                        <label class="form-label" for="description">{{ __('suppliers.description') }}</label>
                        <textarea name="description" id="description" class="form-control">{{ $item->description ?? old('description') }}</textarea>
                        @error('description')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('short_description') is-invalid @enderror">
                        <label class="form-label" for="short_description">{{ __('suppliers.short_description') }}</label>
                        <textarea name="short_description" id="short_description" class="form-control">{{ $item->short_description ?? old('short_description') }}</textarea>
                        @error('short_description')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('url') is-invalid @enderror">
                        <label class="form-label" for="url">{{ __('suppliers.url') }}</label>
                        <input type="url" name="url" id="url" class="form-control" value="{{ $item->url ?? old('url') }}">
                        @error('url')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('profission_guide') is-invalid @enderror">
                        <label class="form-label" for="profission_guide">{{ __('suppliers.profission_guide') }}</label>
                        <input type="text" name="profission_guide" id="profission_guide" class="form-control" value="{{ $item->profission_guide ?? old('profission_guide') }}">
                        @error('profission_guide')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('job') is-invalid @enderror">
                        <label class="form-label" for="job">{{ __('suppliers.job') }}</label>
                        <input type="text" name="job" id="job" class="form-control" value="{{ $item->job ?? old('job') }}">
                        @error('job')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('experience_info') is-invalid @enderror">
                        <label class="form-label" for="experience_info">{{ __('suppliers.experience_info') }}</label>
                        <textarea name="experience_info" id="experience_info" class="form-control">{{ $item->experience_info ?? old('experience_info') }}</textarea>
                        @error('experience_info')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('languages') is-invalid @enderror">
                        <label class="form-label" for="languages">{{ __('suppliers.languages') }}</label>
                        <input type="text" name="languages" id="languages" class="form-control" value="{{ $item->languages ?? old('languages') }}">
                        @error('languages')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('banck_name') is-invalid @enderror">
                        <label class="form-label" for="banck_name">{{ __('suppliers.banck_name') }}</label>
                        <input type="text" name="banck_name" id="banck_name" class="form-control" value="{{ $item->banck_name ?? old('banck_name') }}">
                        @error('banck_name')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('banck_number') is-invalid @enderror">
                        <label class="form-label" for="banck_number">{{ __('suppliers.banck_number') }}</label>
                        <input type="text" name="banck_number" id="banck_number" class="form-control" value="{{ $item->banck_number ?? old('banck_number') }}">
                        @error('banck_number')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('tax_number') is-invalid @enderror">
                        <label class="form-label" for="tax_number">{{ __('suppliers.tax_number') }}</label>
                        <input type="text" name="tax_number" id="tax_number" class="form-control" value="{{ $item->tax_number ?? old('tax_number') }}">
                        @error('tax_number')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('place_summary') is-invalid @enderror">
                        <label class="form-label" for="place_summary">{{ __('suppliers.place_summary') }}</label>
                        <textarea name="place_summary" id="place_summary" class="form-control">{{ $item->place_summary ?? old('place_summary') }}</textarea>
                        @error('place_summary')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('place_content') is-invalid @enderror">
                        <label class="form-label" for="place_content">{{ __('suppliers.place_content') }}</label>
                        <textarea name="place_content" id="place_content" class="form-control">{{ $item->place_content ?? old('place_content') }}</textarea>
                        @error('place_content')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('expectations') is-invalid @enderror">
                        <label class="form-label" for="expectations">{{ __('suppliers.expectations') }}</label>
                        <textarea name="expectations" id="expectations" class="form-control">{{ $item->expectations ?? old('expectations') }}</textarea>
                        @error('expectations')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('general_name') is-invalid @enderror">
                        <label class="form-label" for="general_name">{{ __('suppliers.general_name') }}</label>
                        <input type="text" name="general_name" id="general_name" class="form-control" value="{{ $item->general_name ?? old('general_name') }}">
                        @error('general_name')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    {{-- ...existing code... --}}

                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success">
                    {{ isset($item) ? __('admin.save') : __('admin.create') }}
                </button>
            </div>
        </div>
    </form>
</div>
@stop

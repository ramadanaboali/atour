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
                    <div class="mb-1 col-md-4 @error('national_id') is-invalid @enderror">
                        <label class="form-label" for="national_id">{{ __('suppliers.national_id') }}</label>
                        <input type="text" name="national_id" id="national_id" class="form-control" value="{{ $item->supplier?->national_id ?? old('national_id') }}">
                        @error('national_id')<span class="error">{{ $message }}</span>@enderror
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
                    <div class="mb-1 col-md-4 @error('general_name') is-invalid @enderror">
                        <label class="form-label" for="general_name">{{ __('suppliers.general_name') }}</label>
                        <input type="text" name="general_name" id="general_name" class="form-control" value="{{ $item->supplier?->general_name ?? old('general_name') }}">
                        @error('general_name')<span class="error">{{ $message }}</span>@enderror
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

                   

                    <div class="mb-1 col-md-4 @error('country_id') is-invalid @enderror">
                        <label class="form-label" for="country_id">{{ __('suppliers.country') }}</label>
                        <select name="country_id" id="country_id" class="form-control ajax_select2" data-ajax--url="{{ route('admin.countries.select') }}">
                            @isset($item->supplier?->country)
                            <option value="{{ $item->supplier?->country?->id }}" selected>{{ $item->supplier?->country?->title }}</option>
                            @endisset
                        </select>
                        @error('country_id')<span class="error">{{ $message }}</span>@enderror
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
                    <div class="mb-1 col-md-4  @error('sub_category_id') is-invalid @enderror">
                        <label class="form-label" for="sub_category_id">{{ __('suppliers.sub_category') }}</label>
                        <select name="sub_category_id[]" id="sub_category_id" class="form-control ajax_select2 extra_field" data-ajax--url="{{ route('admin.sub_categories.select') }}" data-ajax--cache="true" multiple>
                            @isset($item->subCategory)
                            @foreach ($item->subCategory as $subCategory)
                            <option value="{{ $subCategory->id }}" selected>{{ $subCategory->title }}</option>
                            @endforeach
                            @endisset
                        </select>
                        @error('sub_category_id')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-1 col-md-4 @error('type') is-invalid @enderror">
                        <label class="form-label" for="type">{{ __('suppliers.type') }}</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">{{ __('suppliers.select_type') }}</option>
                            <option value="individual" {{ ($item->type ?? old('type')) == 'individual' ? 'selected' : '' }}>{{ __('suppliers.individual') }}</option>
                            <option value="company" {{ ($item->type ?? old('type')) == 'company' ? 'selected' : '' }}>{{ __('suppliers.company') }}</option>
                        </select>
                        @error('type')<span class="error">{{ $message }}</span>@enderror
                    </div>


                    <div class="mb-1 col-md-4 @error('streat') is-invalid @enderror">
                        <label class="form-label" for="streat">{{ __('suppliers.streat') }}</label>
                        <input type="text" name="streat" id="streat" class="form-control" value="{{ $item->supplier?->streat ?? old('streat') }}">
                        @error('streat')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('postal_code') is-invalid @enderror">
                        <label class="form-label" for="postal_code">{{ __('suppliers.postal_code') }}</label>
                        <input type="text" name="postal_code" id="postal_code" class="form-control" value="{{ $item->supplier?->postal_code ?? old('postal_code') }}">
                        @error('postal_code')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-1 col-md-4 @error('url') is-invalid @enderror">
                        <label class="form-label" for="url">{{ __('suppliers.url') }}</label>
                        <input type="url" name="url" id="url" class="form-control" value="{{ $item->supplier?->url ?? old('url') }}">
                        @error('url')<span class="error">{{ $message }}</span>@enderror
                    </div>

                  

                    <div class="mb-1 col-md-4 @error('job') is-invalid @enderror">
                        <label class="form-label" for="job">{{ __('suppliers.job') }}</label>
                        <input type="text" name="job" id="job" class="form-control" value="{{ $item->supplier?->job ?? old('job') }}">
                        @error('job')<span class="error">{{ $message }}</span>@enderror
                    </div>


                    <div class="mb-1 col-md-4 @error('bank_name') is-invalid @enderror">
                        <label class="form-label" for="bank_name">{{ __('suppliers.bank_name') }}</label>
                        <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ $item->supplier?->bank_name ?? old('bank_name') }}">
                        @error('bank_name')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('bank_number') is-invalid @enderror">
                        <label class="form-label" for="bank_number">{{ __('suppliers.bank_number') }}</label>
                        <input type="text" name="bank_number" id="bank_number" class="form-control" value="{{ $item->supplier?->bank_number ?? old('bank_number') }}">
                        @error('bank_number')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('tax_number') is-invalid @enderror">
                        <label class="form-label" for="tax_number">{{ __('suppliers.tax_number') }}</label>
                        <input type="text" name="tax_number" id="tax_number" class="form-control" value="{{ $item->supplier?->tax_number ?? old('tax_number') }}">
                        @error('tax_number')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('place_summary') is-invalid @enderror">
                        <label class="form-label" for="place_summary">{{ __('suppliers.place_summary') }}</label>
                        <textarea name="place_summary" id="place_summary" class="form-control">{{ $item->supplier?->place_summary ?? old('place_summary') }}</textarea>
                        @error('place_summary')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('place_content') is-invalid @enderror">
                        <label class="form-label" for="place_content">{{ __('suppliers.place_content') }}</label>
                        <textarea name="place_content" id="place_content" class="form-control">{{ $item->supplier?->place_content ?? old('place_content') }}</textarea>
                        @error('place_content')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('expectations') is-invalid @enderror">
                        <label class="form-label" for="expectations">{{ __('suppliers.expectations') }}</label>
                        <textarea name="expectations" id="expectations" class="form-control">{{ $item->supplier?->expectations ?? old('expectations') }}</textarea>
                        @error('expectations')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('experience_info') is-invalid @enderror">
                        <label class="form-label" for="experience_info">{{ __('suppliers.experience_info') }}</label>
                        <textarea name="experience_info" id="experience_info" class="form-control">{{ $item->supplier?->experience_info ?? old('experience_info') }}</textarea>
                        @error('experience_info')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-1 col-md-4 @error('description') is-invalid @enderror">
                        <label class="form-label" for="description">{{ __('suppliers.description') }}</label>
                        <textarea name="description" id="description" class="form-control">{{ $item->supplier?->description ?? old('description') }}</textarea>
                        @error('description')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <div class="mb-1 col-md-4 @error('rerequest_reason') is-invalid @enderror">
                        <label class="form-label" for="rerequest_reason">{{ __('suppliers.rerequest_reason') }}</label>
                        <textarea name="rerequest_reason" id="rerequest_reason" class="form-control">{{ $item->supplier?->rerequest_reason ?? old('rerequest_reason') }}</textarea>
                        @error('rerequest_reason')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('cover') is-invalid @enderror">
                        <label class="form-label" for="cover">{{ __('suppliers.file') }}</label>
                        <input type="file" class="form-control input" name="cover" id="cover">
                        @error('cover')
                        <span class="error">{{ $message }}</span>
                        @enderror
                        <div>
                            <br>
                            @if(isset($item) && !empty($item->supplier?->photo))

                            <img src="{{ $item->supplier?->photo }}" class="img-fluid img-thumbnail">
                            @endif
                        </div>
                    </div>

                    <div class="mb-1 col-md-4 @error('licence_image') is-invalid @enderror">
                        <label class="form-label" for="licence_image">{{ __('suppliers.licence_image') }}</label>
                        <input type="file" name="licence_image" id="licence_image" class="form-control">
                        @if(isset($item) && !empty($item->supplier?->licence_file))

                        <img src="{{ $item->supplier?->licence_file }}" class="img-fluid img-thumbnail mt-1">

                        @endif
                        @error('licence_image')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-1 col-md-4 @error('image') is-invalid @enderror">
                        <label class="form-label" for="image">{{ __('suppliers.image') }}</label>
                        <input type="file" name="image" id="image" class="form-control">
                        @if(isset($item) && !empty($item->photo))
                        <img src="{{ $item->photo }}" class="img-fluid img-thumbnail mt-1">
                        @endif
                        @error('image')<span class="error">{{ $message }}</span>@enderror
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

@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('offers.plural') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.offers.update', $item->id) : route('admin.offers.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('offers.actions.edit') : __('offers.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('offers.actions.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-4  @error('title_en') is-invalid @enderror">
                            <label class="form-label" for="title_en">{{ __('admin.title_en') }}</label>
                            <input type="text" name="title_en" id="title_en" class="form-control" placeholder=""
                                   value="{{ $item->title_en ?? old('title_en') }}" required/>
                            @error('title_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('title_ar') is-invalid @enderror">
                            <label class="form-label" for="title_ar">{{ __('admin.title_ar') }}</label>
                            <input type="text" name="title_ar" id="title_ar" class="form-control" placeholder=""
                                   value="{{ $item->title_ar ?? old('title_ar') }}" required/>
                            @error('title_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('discount') is-invalid @enderror">
                            <label class="form-label" for="discount">{{ __('offers.discount') }}</label>
                            <input type="number" name="discount" id="discount" class="form-control" placeholder=""
                                   value="{{ $item->discount ?? old('discount') }}" required/>
                            @error('discount')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                         <div class="mb-1 col-md-4  @error('start_date') is-invalid @enderror">
                            <label class="form-label" for="start_date">{{ __('articles.start_date') }}</label>
                            <input type="text" name="start_date" id="start_date" class="form-control flatpickr-basic" placeholder=""
                                   value="{{ $item->start_date ?? old('start_date') }}" required/>
                            @error('start_date')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('end_date') is-invalid @enderror">
                            <label class="form-label" for="end_date">{{ __('articles.end_date') }}</label>
                            <input type="text" name="end_date" id="end_date" class="form-control flatpickr-basic" placeholder=""
                                   value="{{ $item->end_date ?? old('end_date') }}" required/>
                            @error('end_date')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('user_id') is-invalid @enderror">
                            <label  class="form-label" for="user_id">{{ __('offers.supplier') }}</label>
                            <select name="user_id" id="user_id" class="form-control ajax_select2 extra_field"
                                data-ajax--url="{{ route('admin.suppliers.select') }}"
                                data-ajax--cache="true" >
                                @isset($item->supplier)
                                    <option value="{{ $item->user_id }}" selected>{{ $item->supplier?->name }}</option>
                                @endisset
                            </select>
                            @error('user_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('services') is-invalid @enderror">
                            <label  class="form-label" for="services">{{ __('offers.services') }}</label>
                            <select name="services[]" id="services" class="form-control ajax_select2 extra_field"
                                data-ajax--url="{{ route('admin.categories.select') }}"
                                data-ajax--cache="true" multiple >
                                @isset($item->categories)
                                    @foreach ($item->categories as $category)
                                    <option value="{{ $category->id }}" selected>{{ $category->title }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('services')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4  @error('active') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active"
                                        value="1" id="active"
                                    @checked($item->active ?? false )/>
                                <label class="form-check-label" for="active">{{ __('offers.active') }}</label>
                            </div>
                            @error('active')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                          <div class="mb-1 col-md-6  @error('description_en') is-invalid @enderror">
                            <label class="form-label" for="description_en">{{ __('admin.description_en') }}</label>
                            <textarea type="text" name="description_en" id="description_en" class="form-control" placeholder="">{{ $item->description_en ?? old('description_en') }}</textarea>
                            @error('description_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6  @error('description_ar') is-invalid @enderror">
                            <label class="form-label" for="description_ar">{{ __('admin.description_ar') }}</label>
                            <textarea type="text" name="description_ar" id="description_ar" class="form-control" placeholder="">{{ $item->description_ar ?? old('description_ar') }}</textarea>
                            @error('description_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('sub_categories.plural') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.sub_categories.update', $item->id) : route('admin.sub_categories.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('sub_categories.actions.edit') : __('sub_categories.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('sub_categories.actions.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-6  @error('title_en') is-invalid @enderror">
                            <label class="form-label" for="title_en">{{ __('admin.title_en') }}</label>
                            <input type="text" name="title_en" id="title_en" class="form-control" placeholder=""
                                   value="{{ $item->title_en ?? old('title_en') }}" required/>
                            @error('title_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6  @error('title_ar') is-invalid @enderror">
                            <label class="form-label" for="title_ar">{{ __('admin.title_ar') }}</label>
                            <input type="text" name="title_ar" id="title_ar" class="form-control" placeholder=""
                                   value="{{ $item->title_ar ?? old('title_ar') }}" required/>
                            @error('title_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                       <div class="mb-1 col-md-6  @error('category') is-invalid @enderror">
                            <label class="form-label" for="category">{{ __('sub_categories.category') }}</label>
                            <select name="category" id="category" class="form-control" required>
                                <option value="">{{ __('admin.select') }}</option>
                                <option value="gift" @isset($item) {{ $item->category=="gift"?'selected':'' }}  @endisset>{{  __('sub_categories.categories.gift') }}</option>
                                <option value="trip" @isset($item) {{ $item->category=="trip"?'selected':'' }}  @endisset>{{  __('sub_categories.categories.trip') }}</option>
                                <option value="effectiveness" @isset($item) {{ $item->category=="effectiveness"?'selected':'' }}  @endisset>{{  __('sub_categories.categories.effectiveness') }}</option>
                            </select>
                            @error('category')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="mb-1 col-md-6  @error('active') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active"
                                        value="1" id="active"
                                    @checked($item->active ?? false )/>
                                <label class="form-check-label" for="active">{{ __('sub_categories.active') }}</label>
                            </div>
                            @error('active')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                </div>
            </div>
        </div>
    </form>
@stop

@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('jobs.plural') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.jobs.update', $item->id) : route('admin.jobs.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('jobs.actions.edit') : __('jobs.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('jobs.actions.save') }}</span>
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
                        <div class="mb-1 col-md-4  @error('location') is-invalid @enderror">
                            <label class="form-label" for="location">{{ __('jobs.location') }}</label>
                            <input type="text" name="location" id="location" class="form-control" placeholder=""
                                   value="{{ $item->location ?? old('location') }}" required/>
                            @error('location')
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
                        <div class="mb-1 col-md-4  @error('department_id') is-invalid @enderror">
                            <label  class="form-label" for="department_id">{{ __('jobs.department') }}</label>
                            <select name="department_id" id="department_id" class="form-control ajax_select2 extra_field"
                                data-ajax--url="{{ route('admin.departments.select') }}"
                                data-ajax--cache="true" >
                            </select>
                            @error('department_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-2  @error('active') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active"
                                        value="1" id="active"
                                    @checked($item->active ?? false )/>
                                <label class="form-check-label" for="active">{{ __('jobs.active') }}</label>
                            </div>
                            @error('active')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

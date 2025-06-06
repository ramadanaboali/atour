@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('adds.plural') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.adds.update', $item->id) : route('admin.adds.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('adds.actions.edit') : __('adds.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('adds.actions.save') }}</span>
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
                            <label class="form-label" for="location">{{ __('adds.location') }}</label>
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
                        <div class="mb-1 col-md-2  @error('active') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active"
                                        value="1" id="active"
                                    @checked($item->active ?? false )/>
                                <label class="form-check-label" for="active">{{ __('adds.active') }}</label>
                            </div>
                            @error('active')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-2  @error('send_notification') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="send_notification"
                                        value="1" id="send_notification"
                                />
                                <label class="form-check-label" for="send_notification">{{ __('adds.send_notification') }}</label>
                            </div>
                            @error('send_notification')
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
                        <div class="mb-1 col-md-6 @error('image') is-invalid @enderror">
                            <label class="form-label" for="image">{{ __('adds.file') }}</label>
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

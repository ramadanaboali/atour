@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('sliders.plural') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.sliders.update', $item->id) : route('admin.sliders.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('sliders.actions.edit') : __('sliders.actions.create') }}</span>
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
                        <div class="mb-1 col-md-4  @error('title_en') is-invalid @enderror">
                            <label class="form-label" for="title_en">{{ __('admin.name_en') }}</label>
                            <input type="text" name="title_en" id="title_en" class="form-control" placeholder=""
                                   value="{{ $item->title_en ?? old('title_en') }}" required/>
                            @error('title_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('title_ar') is-invalid @enderror">
                            <label class="form-label" for="title_ar">{{ __('admin.name_ar') }}</label>
                            <input type="text" name="title_ar" id="title_ar" class="form-control" placeholder=""
                                   value="{{ $item->title_ar ?? old('title_ar') }}" required/>
                            @error('title_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('url') is-invalid @enderror">
                            <label class="form-label" for="url">{{ __('sliders.url') }}</label>
                            <input type="text" name="url" id="url" class="form-control" placeholder=""
                                   value="{{ $item->url ?? old('url') }}" >
                            @error('url')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4 @error('image') is-invalid @enderror">
                            <label class="form-label" for="image">{{ __('sliders.file') }}</label>
                            <input type="file" class="form-control input" name="image" id="image">
                            @error('image')
                            <span class="error">{{ $message }}</span>
                            @enderror
                            <div>
                                <br>
                                @if(isset($item) && !empty($item->photo))
                                    <img src="{{ $item->photo }}" class="img-fluid img-thumbnail">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

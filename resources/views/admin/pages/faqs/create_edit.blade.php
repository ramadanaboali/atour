@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('faqs.plural') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.faqs.update', $item->id) : route('admin.faqs.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('faqs.actions.edit') : __('faqs.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('faqs.actions.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-12  @error('question') is-invalid @enderror">
                            <label class="form-label" for="question">{{ __('admin.question') }}</label>
                            <input type="text" name="question" id="question" class="form-control" placeholder=""
                                   value="{{ $item->question ?? old('question') }}" required/>
                            @error('question')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                           <div class="mb-1 col-md-12  @error('answer') is-invalid @enderror">
                            <label class="form-label" for="answer">{{ __('admin.answer') }}</label>
                            <textarea type="text" name="answer" id="answer" class="form-control editor" placeholder="">{{ $item->answer ?? old('answer') }}</textarea>
                            @error('answer')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

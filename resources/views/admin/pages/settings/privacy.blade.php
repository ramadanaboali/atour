@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('settings.privacy') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form" action="{{ route('admin.settings.update') }}">
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="type" value="privacy">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ __('settings.privacy') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                                <i data-feather="save"></i>
                                <span class="active-sorting text-primary">{{ __('admin.actions.save') }}</span>
                            </button>
                        </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-12 @error('privacy_content_en') is-invalid @enderror">
                            <label class="form-label" for="privacy_content_en">
                                <span class="required">{{ __('settings.content_en') }}</span>
                            </label>
                            <?php $privacyContent_en = $items->where('key', 'privacy_content_en')->first()->value ?? old('privacy_content_en'); ?>
                            <textarea type="text" class="form-control form-control-solid editor" name="privacy_content_en" id="privacy_content_en">{!! $privacyContent_en !!}</textarea>
                            @error('privacy_content_en')
                            <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-1 col-md-12 @error('privacy_content_ar') is-invalid @enderror">
                            <label class="form-label" for="privacy_content_ar">
                                <span class="required">{{ __('settings.content_ar') }}</span>
                            </label>
                            <?php $privacyContent = $items->where('key', 'privacy_content_ar')->first()->value ?? old('privacy_content_ar'); ?>
                            <textarea type="text" class="form-control form-control-solid editor" name="privacy_content_ar" id="privacy_content_ar">{!! $privacyContent !!}</textarea>
                            @error('privacy_content_ar')
                            <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>
    <script>
        CKEDITOR.editorConfig = function( config ) {
            config.language = 'es';
            config.uiColor = '#F7B42C';
            config.height = 200;
            config.toolbarCanCollapse = true;
        };
        var editor = CKEDITOR.replaceAll( 'editor' );
    </script>
@endpush

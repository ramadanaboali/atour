@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('admin.footer_settings') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form" action="{{ route('admin.settings.update') }}">
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="type" value="general">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ __('admin.footer_settings') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
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
                    <div class="row mt-2">
                        <div class="mb-1 col-md-6 @error('footer_facebook') is-invalid @enderror">
                                <?php $footer_facebook = $items->where('key', 'footer_facebook')->first()->value ?? old('footer_facebook'); ?>
                            <label class="form-label" for="footer_facebook">{{ __('settings.facebook') }}</label>
                            <input type="text" name="footer_facebook" id="footer_facebook" class="form-control" placeholder=""
                                   value="{{ $footer_facebook ?? old('footer_facebook') }}"/>
                            @error('footer_facebook')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('footer_twitter') is-invalid @enderror">
                                <?php $footer_twitter = $items->where('key', 'footer_twitter')->first()->value ?? old('footer_twitter'); ?>
                            <label class="form-label" for="footer_twitter">{{ __('settings.twitter') }}</label>
                            <input type="text" name="footer_twitter" id="footer_twitter" class="form-control" placeholder=""
                                   value="{{ $footer_twitter ?? old('footer_twitter') }}"/>
                            @error('footer_twitter')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('footer_instagram') is-invalid @enderror">
                                <?php $footer_instagram = $items->where('key', 'footer_instagram')->first()->value ?? old('footer_instagram'); ?>
                            <label class="form-label" for="footer_instagram">{{ __('settings.instagram') }}</label>
                            <input type="text" name="footer_instagram" id="footer_instagram" class="form-control" placeholder=""
                                   value="{{ $footer_instagram ?? old('footer_instagram') }}"/>
                            @error('footer_instagram')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('footer_snapchat') is-invalid @enderror">
                                <?php $footer_snapchat = $items->where('key', 'footer_snapchat')->first()->value ?? old('footer_snapchat'); ?>
                            <label class="form-label" for="footer_snapchat">{{ __('settings.snapchat') }}</label>
                            <input type="text" name="footer_snapchat" id="footer_snapchat" class="form-control" placeholder=""
                                   value="{{ $footer_snapchat ?? old('footer_snapchat') }}"/>
                            @error('footer_snapchat')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('footer_tiktok') is-invalid @enderror">
                                <?php $footer_tiktok = $items->where('key', 'footer_tiktok')->first()->value ?? old('footer_tiktok'); ?>
                            <label class="form-label" for="footer_tiktok">{{ __('settings.tiktok') }}</label>
                            <input type="text" name="footer_tiktok" id="footer_tiktok" class="form-control" placeholder=""
                                   value="{{ $footer_tiktok ?? old('footer_tiktok') }}"/>
                            @error('footer_tiktok')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('footer_google_play') is-invalid @enderror">
                                <?php $footer_google_play = $items->where('key', 'footer_google_play')->first()->value ?? old('footer_google_play'); ?>
                            <label class="form-label" for="footer_google_play">{{ __('settings.google_play') }}</label>
                            <input type="text" name="footer_google_play" id="footer_google_play" class="form-control" placeholder=""
                                   value="{{ $footer_google_play ?? old('footer_google_play') }}"/>
                            @error('footer_google_play')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('footer_app_store') is-invalid @enderror">
                                <?php $footer_app_store = $items->where('key', 'footer_app_store')->first()->value ?? old('footer_app_store'); ?>
                            <label class="form-label" for="footer_app_store">{{ __('settings.app_store') }}</label>
                            <input type="text" name="footer_app_store" id="footer_app_store" class="form-control" placeholder=""
                                   value="{{ $footer_app_store ?? old('footer_app_store') }}"/>
                            @error('footer_app_store')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

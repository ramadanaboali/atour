@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('settings.plural') }}</title>
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
                            <span>{{ __('settings.plural') }}</span>
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
                        {{-- <div class="mb-1 col-md-6 @error('general_currency_name_en') is-invalid @enderror">
                                <?php $general_currency_name_en = $items->where('key', 'general_currency_name_en')->first()->value ?? old('general_currency_name_en'); ?>
                            <label class="form-label" for="general_currency_name_en">{{ __('settings.currency_name_en') }}</label>
                            <input type="text" name="general_currency_name_en" id="general_currency_name_en" class="form-control" placeholder=""
                                   value="{{ $general_currency_name_en ?? old('general_currency_name_en') }}"/>
                            @error('general_currency_name_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('general_currency_name_ar') is-invalid @enderror">
                                <?php $general_currency_name_ar = $items->where('key', 'general_currency_name_ar')->first()->value ?? old('general_currency_name_ar'); ?>
                            <label class="form-label" for="general_currency_name_ar">{{ __('settings.currency_name_ar') }}</label>
                            <input type="text" name="general_currency_name_ar" id="general_currency_name_ar" class="form-control" placeholder=""
                                   value="{{ $general_currency_name_ar ?? old('general_currency_name_ar') }}"/>
                            @error('general_currency_name_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('general_currency_code_en') is-invalid @enderror">
                                <?php $general_currency_code_en = $items->where('key', 'general_currency_code_en')->first()->value ?? old('general_currency_code_en'); ?>
                            <label class="form-label" for="general_currency_code_en">{{ __('settings.currency_code_en') }}</label>
                            <input type="text" name="general_currency_code_en" id="general_currency_code_en" class="form-control" placeholder=""
                                   value="{{ $general_currency_code_en ?? old('general_currency_code_en') }}"/>
                            @error('general_currency_code_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('general_currency_code_ar') is-invalid @enderror">
                                <?php $general_currency_code_ar = $items->where('key', 'general_currency_code_ar')->first()->value ?? old('general_currency_code_ar'); ?>
                            <label class="form-label" for="general_currency_code_ar">{{ __('settings.currency_code_ar') }}</label>
                            <input type="text" name="general_currency_code_ar" id="general_currency_code_ar" class="form-control" placeholder=""
                                   value="{{ $general_currency_code_ar ?? old('general_currency_code_ar') }}"/>
                            @error('general_currency_code_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div> --}}
                        <div class="mb-1 col-md-12 @error('about_image') is-invalid @enderror">
                            <label class="form-label" for="about_image">{{ __('settings.image') }}</label>
                            <input type="file" class="form-control input" name="about_image" id="about_image">
                            <?php $aboutImage = $items->where('key', 'about_image')->first()->value ?? old('settings.image'); ?>
                            @error('about_image')
                            <span class="error">{{ $message }}</span>
                            @enderror
                            <div>
                                <br>
                                @if( !empty($aboutImage))
                                    <img src="{{  asset('storage/settings/' .$aboutImage) }}" class="img-fluid img-thumbnail" width="120px">
                                @endif
                            </div>
                        </div>

                        <div class="mb-1 col-md-6 @error('general_email') is-invalid @enderror">
                                <?php $generalPhone = $items->where('key', 'general_email')->first()->value ?? old('general_email'); ?>
                            <label class="form-label" for="general_email">{{ __('settings.email') }}</label>
                            <input type="email" name="general_email" id="general_email" class="form-control" placeholder=""
                                   value="{{ $generalPhone ?? old('general_email') }}"/>
                            @error('general_email')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('general_whatsapp') is-invalid @enderror">
                                <?php $generalPhone = $items->where('key', 'general_whatsapp')->first()->value ?? old('general_whatsapp'); ?>
                            <label class="form-label" for="general_whatsapp">{{ __('settings.whatsapp') }}</label>
                            <input type="text" name="general_whatsapp" id="general_whatsapp" class="form-control" placeholder=""
                                   value="{{ $generalPhone ?? old('general_whatsapp') }}"/>
                            @error('general_whatsapp')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('general_phone') is-invalid @enderror">
                                <?php $generalPhone = $items->where('key', 'general_phone')->first()->value ?? old('general_phone'); ?>
                            <label class="form-label" for="general_phone">{{ __('settings.phone') }}</label>
                            <input type="text" name="general_phone" id="general_phone" class="form-control" placeholder=""
                                   value="{{ $generalPhone ?? old('general_phone') }}"/>
                            @error('general_phone')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('general_facebook_url') is-invalid @enderror">
                                <?php $generalFacebook = $items->where('key', 'general_facebook_url')->first()->value ?? old('general_facebook_url'); ?>
                            <label class="form-label" for="general_facebook_url">{{ __('settings.facebook') }}</label>
                            <input type="text" name="general_facebook_url" id="general_facebook_url" class="form-control" placeholder=""
                                   value="{{ $generalFacebook ?? old('general_facebook_url') }}"/>
                            @error('general_facebook_url')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('general_youtube_url') is-invalid @enderror">
                                <?php $general_youtube_url = $items->where('key', 'general_youtube_url')->first()->value ?? old('general_youtube_url'); ?>
                            <label class="form-label" for="general_youtube_url">{{ __('settings.youtube') }}</label>
                            <input type="text" name="general_youtube_url" id="general_youtube_url" class="form-control" placeholder=""
                                   value="{{ $general_youtube_url ?? old('general_youtube_url') }}"/>
                            @error('general_youtube_url')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('general_linkedin_url') is-invalid @enderror">
                                <?php $general_linkedin_url = $items->where('key', 'general_linkedin_url')->first()->value ?? old('general_linkedin_url'); ?>
                            <label class="form-label" for="general_linkedin_url">{{ __('settings.linkedin') }}</label>
                            <input type="text" name="general_linkedin_url" id="general_linkedin_url" class="form-control" placeholder=""
                                   value="{{ $general_linkedin_url ?? old('general_linkedin_url') }}"/>
                            @error('general_linkedin_url')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('general_twitter') is-invalid @enderror">
                                <?php $generalTwitter = $items->where('key', 'general_twitter')->first()->value ?? old('general_twitter'); ?>
                            <label class="form-label" for="general_twitter">{{ __('settings.twitter') }}</label>
                            <input type="text" name="general_twitter" id="general_twitter" class="form-control" placeholder=""
                                   value="{{ $generalTwitter ?? old('general_twitter') }}"/>
                            @error('general_twitter')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('general_instagram') is-invalid @enderror">
                                <?php $generalInstagram = $items->where('key', 'general_instagram')->first()->value ?? old('general_instagram'); ?>
                            <label class="form-label" for="general_instagram">{{ __('settings.instagram') }}</label>
                            <input type="text" name="general_instagram" id="general_instagram" class="form-control" placeholder=""
                                   value="{{ $generalInstagram ?? old('general_instagram') }}"/>
                            @error('general_instagram')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('general_google_url') is-invalid @enderror">
                                <?php $generalAddress = $items->where('key', 'general_google_url')->first()->value ?? old('general_google_url'); ?>
                            <label class="form-label" for="general_google_url">{{ __('settings.google_url') }}</label>
                            <input type="text" name="general_google_url" id="general_google_url" class="form-control" placeholder=""
                                   value="{{ $generalAddress ?? old('general_google_url') }}"/>
                            @error('general_google_url')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

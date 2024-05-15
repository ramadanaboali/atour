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
                    <div class="row mt-2">

                       <div class="mb-1 col-md-12 @error('header_logo') is-invalid @enderror">
                            <label class="form-label" for="header_logo">{{ __('settings.image') }}</label>
                            <input type="file" class="form-control input" name="header_logo" id="header_logo">
                            <?php $header_logo = $items->where('key', 'header_logo')->first()->value ?? old('settings.image'); ?>
                            @error('header_logo')
                            <span class="error">{{ $message }}</span>
                            @enderror
                            <div>
                                <br>
                                @if( !empty($header_logo))
                                    <img src="{{  asset('storage/settings/' .$header_logo) }}" class="img-fluid img-thumbnail" width="120px">
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('offers.plural') }}</title>
@endsection
@section('content')

        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{  __('offers.actions.show') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">

                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-4 ">
                            <label class="form-label" for="title_en">{{ __('admin.title_en') }} : {{ $item->title_en }}</label>
                        </div>
                        <div class="mb-1 col-md-4 ">
                            <label class="form-label" for="title_ar">{{ __('admin.title_ar') }} : {{ $item->title_ar }}</label>
                        </div>

                        <div class="mb-1 col-md-4 ">
                            <label class="form-label" for="discount">{{ __('admin.discount') }} : {{ $item->discount }}</label>
                        </div>
                        <div class="mb-1 col-md-4 ">
                            <label class="form-label" for="start_date">{{ __('articles.start_date') }} : {{ $item->start_date }}</label>
                        </div>
                        <div class="mb-1 col-md-4 ">
                            <label class="form-label" for="end_date">{{ __('articles.end_date') }} : {{ $item->end_date }}</label>
                        </div>
                        <div class="mb-1 col-md-4 ">
                            <label class="form-label" for="supplier">{{ __('offers.supplier') }} : {{ $item->supplier?->user?->name }}</label>
                        </div>

                        <div class="mb-1 col-md-4  @error('active') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active"
                                value="1" id="active" disabled
                                @checked($item->active ?? false )/>
                                <label class="form-check-label" for="active">{{ __('offers.active') }}</label>
                            </div>
                            @error('active')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        @foreach ($item->categories as $service)
                        <div class="mb-1 col-md-4 ">
                            <label class="form-label" for="service">{{ __('offers.service_name') }} : {{ $service->title }}</label>
                        </div>
                        @endforeach
                        <div class="mb-1 col-md-6 ">
                            <label class="form-label" for="description_en">{{ __('admin.description_en') }} : {{ $item->description_en }}</label>
                        </div>
                        <div class="mb-1 col-md-6 ">
                            <label class="form-label" for="description_ar">{{ __('admin.description_ar') }} : {{ $item->description_ar }}</label>
                        </div>
                    </div>
                    <div class="row">
                        {{-- <hr> --}}





                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

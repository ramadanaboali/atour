@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('users.plural') }}</title>
@endsection
@section('content')
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{  __('users.actions.show')  }}</span>
                        </h1>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-3  @error('name') is-invalid @enderror">
                            <label class="form-label">{{ __('users.name') }} : </label>
                            <label class="form-label">{{ $item->name  }}</label>
                        </div>

                        <div class="mb-1 col-md-3  @error('email') is-invalid @enderror">
                            <label class="form-label">{{ __('users.email') }}  :  </label>
                            <label class="form-label">{{ $item->email }}</label>
                        </div>

                        <div class="mb-1 col-md-3  @error('type') is-invalid @enderror">
                            <label class="form-label">{{ __('users.type') }} : </label>
                            <label class="form-label">{{ __('users.types.'.$item->type) }}</label>
                        </div>

                        <div class="mb-1 col-md-3  @error('phone') is-invalid @enderror">
                            <label class="form-label">{{ __('users.phone') }} : </label>
                            <label class="form-label">{{ $item->phone }}</label>
                        </div>
                    </div>
                    <hr>
                    <hr>
                    <div class="row">
                        <div class="mb-1 col-md-4 @error('image') is-invalid @enderror">
                            <label class="form-label" for="image">{{ __('users.image') }}</label>
                            <div>
                                <br>
                                @if(!empty($item->image))
                                    <img src="{{ $item->photo }}"
                                         class="img-fluid img-thumbnail">
                                @endif
                            </div>
                        </div>
                        <div class="mb-1 col-md-4 @error('passport') is-invalid @enderror">
                            <label class="form-label" for="passport">{{ __('users.passport') }}</label>
                            <div>
                                <br>
                                 @if(isset($item) && !empty($item->passport))
                                    @if(pathinfo($item->passport, PATHINFO_EXTENSION)=='pdf')
                                        <a href="{{ $item->passport }}" download>
                                        <img src="{{ asset('default.jpg') }}" class="img-fluid img-thumbnail">
                                        </a>
                                        @else
                                        <a href="{{ $item->passport }}" download>
                                        <img src="{{ $item->passport }}" class="img-fluid img-thumbnail">
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="mb-1 col-md-4 @error('licence') is-invalid @enderror">
                            <label class="form-label" for="licence">{{ __('users.licence') }}</label>
                            <div>
                                <br>
                                @if(isset($item) && !empty($item->licence))
                                    @if(pathinfo($item->licence, PATHINFO_EXTENSION)=='pdf')
                                        <a href="{{ $item->licence }}" download>
                                        <img src="{{ asset('default.jpg') }}" class="img-fluid img-thumbnail">
                                        </a>
                                        @else
                                        <a href="{{ $item->licence }}" download>
                                        <img src="{{ $item->licence }}" class="img-fluid img-thumbnail">
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

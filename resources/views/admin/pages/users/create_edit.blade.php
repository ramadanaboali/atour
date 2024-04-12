@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('users.plural') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.users.update', $item->id) : route('admin.users.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('users.actions.edit') : __('users.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('users.actions.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-4  @error('name') is-invalid @enderror">
                            <label class="form-label">{{ __('users.name') }}</label>
                            <input class="form-control" name="name" type="text" value="{{ $item->name ?? old('name') }}">
                            @error('name')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4  @error('email') is-invalid @enderror">
                            <label class="form-label">{{ __('users.email') }}</label>
                            <input class="form-control input" name="email"  placeholder="" type="email" value="{{ $item->email ?? old('email') }}"
                                   autocomplete="false" readonly onfocus="this.removeAttribute('readonly');">
                            @error('email')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('password') is-invalid @enderror">
                            <label class="form-label">{{ __('users.password') }}</label>
                            <input class="form-control input" name="password"  placeholder="" type="password"
                                   autocomplete="false" readonly onfocus="this.removeAttribute('readonly');">
                            @error('password')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('type') is-invalid @enderror">
                            <label class="form-label">{{ __('users.type') }}</label>
                            <select class="form-control input" name="type">
                                <option value="">{{ __('users.select') }}</option>
                                    <option value="1" {{ ($item->type ?? null) == 1 ? 'selected' : '' }}>{{ __('users.types.1') }}</option>
                                    <option value="2" {{ ($item->type ?? null) == 2 ? 'selected' : '' }}>{{ __('users.types.2') }}</option>
                            </select>
                            @error('type')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4  @error('phone') is-invalid @enderror">
                            <label class="form-label">{{ __('users.phone') }}</label>
                            <input class="form-control" name="phone" type="text" value="{{ $item->phone ?? old('phone') }}">
                            @error('phone')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="mb-1 col-md-4 @error('image') is-invalid @enderror">
                            <label class="form-label" for="image">{{ __('users.image') }}</label>
                            <input type="file" class="form-control input" name="image" id="image">
                            @error('image')
                            <span class="error">{{ $message }}</span>
                            @enderror
                            <div>
                                <br>
                                @if(!empty($item->image))
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

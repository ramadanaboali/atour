@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('notifications.plural') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.notifications.update', $item->id) : route('admin.notifications.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('notifications.actions.edit') : __('notifications.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('notifications.actions.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-4  @error('company_id') is-invalid @enderror">
                            <label class="form-label" for="company_id">{{ __('notifications.companies') }}</label>

                            <select name="company_id[]" id="company_id" class="select2-input form-select select2-input-hidden-accessibl"
                                   multiple>
                                   <option value="all">الكل</option>
                                   @foreach ($companies as $company)
                                   <option value="{{ $company->id }}" >{{ $company->text }}</option>
                                   @endforeach
                            </select>
                            @error('company_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('client_id') is-invalid @enderror">
                            <label class="form-label" for="client_id">{{ __('notifications.clientes') }}</label>
                            <select name="client_id[]" id="client_id" class="select2-input form-select select2-hidden-accessibl "
                                    multiple>
                                 <option value="all">الكل</option>
                                   @foreach ($clients as $client)
                                   <option value="{{ $client->id }}" >{{ $client->text }}</option>
                                   @endforeach                            </select>
                            @error('client_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-12  @error('text') is-invalid @enderror">
                            <label class="form-label" for="text">{{ __('notifications.text') }}</label>
                            <textarea name="text" id="text" class="form-control" placeholder="">

                            </textarea>

                            @error('url')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

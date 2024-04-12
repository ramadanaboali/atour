@extends('admin.layouts.master')
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.roles.update', $item->id) : route('admin.roles.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('roles.actions.edit') : __('roles.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('roles.actions.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-12  @error('display_name') is-invalid @enderror">
                            <label class="form-label">{{ __('roles.display_name') }}</label>
                            <input class="form-control" name="display_name" type="text" value="{{ $item->display_name ?? old('display_name') }}">
                            @error('display_name')
                            <span class="error">{{ $message }}</span>
                            @enderror
                            <input class="form-control" name="name" type="hidden" value="{{ $item->name ?? old('name') }}">

                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @foreach($permissionsGroups as $key => $group)
                            <div class="col-md-4 mb-2">
                                <h3 class="mb-2">{{ __($key.'.plural') }}</h3>
                                <div>
                                    @foreach($group as $permission)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                   value="{{ $permission->id }}" id="permissions{{ $permission->id }}"
                                                @checked(in_array($permission->id, $itemPermissions))/>
                                                <?php $key = explode('.', $permission->display_name); ?>
                                            <label class="form-check-label" for="permissions{{ $permission->id }}">{{ isset($key[1]) ? __('admin.'.$key[1]) : $permission->display_name  }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

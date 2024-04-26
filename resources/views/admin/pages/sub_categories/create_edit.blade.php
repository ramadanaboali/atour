@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('sub_categories.plural') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.sub_categories.update', $item->id) : route('admin.sub_categories.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('sub_categories.actions.edit') : __('sub_categories.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('sub_categories.actions.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-6  @error('title_en') is-invalid @enderror">
                            <label class="form-label" for="title_en">{{ __('admin.title_en') }}</label>
                            <input type="text" name="title_en" id="title_en" class="form-control" placeholder=""
                                   value="{{ $item->title_en ?? old('title_en') }}" required/>
                            @error('title_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6  @error('title_ar') is-invalid @enderror">
                            <label class="form-label" for="title_ar">{{ __('admin.title_ar') }}</label>
                            <input type="text" name="title_ar" id="title_ar" class="form-control" placeholder=""
                                   value="{{ $item->title_ar ?? old('title_ar') }}" required/>
                            @error('title_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                       <div class="mb-1 col-md-6  @error('category_id') is-invalid @enderror">
                            <label class="form-label" for="category_id">{{ __('sub_categories.category') }}</label>
                            <select name="category_id" id="category_id" class="form-control ajax_select2 extra_field"
                                    data-ajax--url="{{ route('admin.categories.select') }}"
                                    data-ajax--cache="true">
                                @isset($item->category)
                                    <option value="{{ $item->category->id }}" selected>{{ $item->category->title }}</option>
                                @endisset
                            </select>
                            @error('category_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <input type="hidden" id="item_id" value="{{ $item->id??null }}">
                       <div class="mb-1 col-md-6  @error('parent_id') is-invalid @enderror">
                            <label class="form-label" for="parent_id">{{ __('sub_categories.plural') }}</label>
                            <select name="parent_id" id="parent_id" class="form-control ajax_select2 extra_field">
                                @isset($item->parent_id)
                                    <option value="{{ $item->parent->id }}" selected>{{ $item->parent->title }}</option>
                                @endisset
                            </select>
                            @error('parent_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-6  @error('active') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active"
                                        value="1" id="active"
                                    @checked($item->active ?? false )/>
                                <label class="form-check-label" for="active">{{ __('sub_categories.active') }}</label>
                            </div>
                            @error('active')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                      
                </div>
            </div>
        </div>
    </form>
@stop

@push('scripts')

<script>
$(window).on('load', function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('change', '#category_id', function(){
            var category_id = $(this).val();
            var item_id = $("#item_id").val();
            $("#parent_id").empty();
            $("#parent_id").select2({
            ajax: {
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {results: data};
                },
                cache: true,
                url: function () {
                return "{{ route('admin.sub_categories.select') }}?category_id="+category_id+"&item_id="+item_id;
                }
            }
        });

    });
});
    </script>

@endpush

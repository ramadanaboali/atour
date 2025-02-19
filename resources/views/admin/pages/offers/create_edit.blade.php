@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('offers.plural') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.offers.update', $item->id) : route('admin.offers.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('offers.actions.edit') : __('offers.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('offers.actions.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-4  @error('title_en') is-invalid @enderror">
                            <label class="form-label" for="title_en">{{ __('admin.title_en') }}</label>
                            <input type="text" name="title_en" id="title_en" class="form-control" placeholder=""
                                   value="{{ $item->title_en ?? old('title_en') }}" required/>
                            @error('title_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('title_ar') is-invalid @enderror">
                            <label class="form-label" for="title_ar">{{ __('admin.title_ar') }}</label>
                            <input type="text" name="title_ar" id="title_ar" class="form-control" placeholder=""
                                   value="{{ $item->title_ar ?? old('title_ar') }}" required/>
                            @error('title_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4  @error('vendor_id') is-invalid @enderror">
                            <label  class="form-label" for="vendor_id">{{ __('offers.supplier') }}</label>
                            <select name="vendor_id" id="vendor_id" class="form-control ajax_select2 extra_field"
                                data-ajax--url="{{ route('admin.suppliers.select') }}" onchange="updateAjaxUrl()"
                                data-ajax--cache="false" >
                                @isset($item->vendor)
                                    <option value="{{ $item->vendor_id }}" selected>{{ $item->vendor?->name }}</option>
                                @endisset
                            </select>
                            @error('vendor_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('type') is-invalid @enderror">
                            <label  class="form-label" for="type">{{ __('offers.type') }}</label>
                            <select name="type" id="type" class="form-control "  onchange="updateAjaxUrl()">
                                <option value="trip" @isset($item->type) {{ $item->type=="trip"?'selected':'' }} @endisset>{{ __('offers.types.trip') }}</option>
                                <option value="gift" @isset($item->type) {{ $item->type=="gift"?'selected':'' }} @endisset>{{ __('offers.types.gift') }}</option>
                                <option value="effectivenes" @isset($item->type) {{ $item->type=="effectivenes"?'selected':'' }} @endisset>{{ __('offers.types.effectivenes') }}</option>
                            </select>
                            @error('type')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                         <div class="mb-1 col-md-4  @error('trip_id') is-invalid @enderror" id="trip_section" style="display: none">
                            <label  class="form-label" for="trip_id">{{ __('offers.types.trip') }}</label>
                            <select name="trip_id" id="trip_id" class="form-control ">
                            </select>
                            @error('trip_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                         <div class="mb-1 col-md-4  @error('gift_id') is-invalid @enderror" id="gift_section" style="display:none">
                            <label  class="form-label" for="gift_id">{{ __('offers.types.gift') }}</label>
                            <select name="gift_id" id="gift_id" class="form-control ">
                            </select>
                            @error('gift_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                         <div class="mb-1 col-md-4  @error('effectivenes_id') is-invalid @enderror" id="effectivenes_section" style="display: none">
                            <label  class="form-label" for="effectivenes_id">{{ __('offers.types.effectivenes') }}</label>
                            <select name="effectivenes_id" id="effectivenes_id" class="form-control ">
                            </select>
                            @error('effectivenes_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                         <div class="mb-1 col-md-4 @error('image') is-invalid @enderror">
                            <label class="form-label" for="image">{{ __('sliders.file') }}</label>
                            <input type="file" class="form-control input" name="image" id="image">
                            @error('image')
                            <span class="error">{{ $message }}</span>
                            @enderror
                            <div>
                                <br>
                                @if(isset($item) && !empty($item->photo))
                                    <img src="{{ $item->photo }}" class="img-fluid img-thumbnail">
                                @endif
                            </div>
                        </div>

                          <div class="mb-1 col-md-6  @error('description_en') is-invalid @enderror">
                            <label class="form-label" for="description_en">{{ __('admin.description_en') }}</label>
                            <textarea type="text" name="description_en" id="description_en" class="form-control" placeholder="">{{ $item->description_en ?? old('description_en') }}</textarea>
                            @error('description_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6  @error('description_ar') is-invalid @enderror">
                            <label class="form-label" for="description_ar">{{ __('admin.description_ar') }}</label>
                            <textarea type="text" name="description_ar" id="description_ar" class="form-control" placeholder="">{{ $item->description_ar ?? old('description_ar') }}</textarea>
                            @error('description_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@stop
@push('scripts')
 <script>
    $(window).on('load', function() {
        updateAjaxUrl();
    });
        function updateAjaxUrl() {

            var vendorSelect = $("#vendor_id").val();
            var type = $("#type").val();
            var trip_id = {{ $item->trip_id ?? 0 }};
            var effectivenes_id = {{ $item->effectivenes_id ?? 0 }};
            var gift_id = {{ $item->gift_id ?? 0 }};


            $.ajax({
                    type:'POST',
                    url:"{{ route('admin.getOffers') }}",
                    data:{vendor_id:vendorSelect,type:type,effectivenes_id:effectivenes_id,trip_id:trip_id,gift_id:gift_id },
                    success:function(data){
                        if(type==="trip"){

                            $("#trip_id").html(data);
                            $("#trip_section").show();
                            $("#effectivenes_section").hide();
                            $("#gift_section").hide();
                        }
                        if(type==="effectivenes"){
                            $("#effectivenes_id").html(data);
                            $("#trip_section").hide();
                            $("#effectivenes_section").show();
                            $("#gift_section").hide();
                        }
                        if(type==="gift"){
                            $("#gift_id").html(data);
                            $("#trip_section").hide();
                            $("#effectivenes_section").hide();
                            $("#gift_section").show();
                        }
                    }
            });
        }
    </script>
@endpush

@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('effectivenes.plural') }}</title>
@endsection
@push('styles')
    <style>
        .price{
            position: absolute;
            top: -12px;
            background: white;
            padding-inline: 2px;
            text-align: center;
        }
input[type="file"] {
  display: block;
}
.imageThumb {
  max-height: 75px;
  border: 2px solid;
  padding: 1px;
  cursor: pointer;
}
.pip {
  display: inline-block;
  margin: 10px 10px 0 0;
}
.remove {
  display: block;
  background: #444;
  border: 1px solid black;
  color: white;
  text-align: center;
  cursor: pointer;
}
.remove:hover {
  background: white;
  color: black;
}

    </style>

@endpush
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.effectivenes.update', $item->id) : route('admin.effectivenes.store') }}">
        @csrf
        @method(isset($item) ? 'PUT' : 'POST')
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('effectivenes.actions.edit') : __('effectivenes.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('effectivenes.actions.save') }}</span>
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
                            <label class="form-label" for="title_en">{{ __('effectivenes.name_en') }}</label>
                            <input type="text" name="title_en" id="title_en" class="form-control" placeholder=""
                                   value="{{ $item->title_en ?? old('title_en') }}" required/>
                            @error('title_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('title_ar') is-invalid @enderror">
                            <label class="form-label" for="title_ar">{{ __('effectivenes.name_ar') }}</label>
                            <input type="text" name="title_ar" id="title_ar" class="form-control" placeholder=""
                                   value="{{ $item->title_ar ?? old('title_ar') }}" required/>
                            @error('title_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('from_date') is-invalid @enderror">
                            <label class="form-label" for="from_date">{{ __('effectivenes.from_date') }}</label>
                            <input type="text" name="from_date" id="from_date" class="form-control flatpickr-basic" placeholder=""
                                   value="{{ $item->from_date ?? old('from_date') }}" required/>
                            @error('from_date')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('to_date') is-invalid @enderror">
                            <label class="form-label" for="to_date">{{ __('effectivenes.to_date') }}</label>
                            <input type="text" name="to_date" id="to_date" class="form-control flatpickr-basic" placeholder=""
                                   value="{{ $item->to_date ?? old('to_date') }}" required/>
                            @error('to_date')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('from_time') is-invalid @enderror">
                            <label class="form-label" for="from_time">{{ __('effectivenes.from_time') }}</label>
                            <input type="text" name="from_time" id="from_time" class="form-control flatpickr-time" placeholder=""
                                   value="{{ $item->from_time ?? old('from_time') }}" required/>
                            @error('from_time')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('to_time') is-invalid @enderror">
                            <label class="form-label" for="to_time">{{ __('effectivenes.to_time') }}</label>
                            <input type="text" name="to_time" id="to_time" class="form-control flatpickr-time" placeholder=""
                                   value="{{ $item->to_time ?? old('to_time') }}" required/>
                            @error('to_time')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('location') is-invalid @enderror">
                            <label class="form-label" for="location">{{ __('effectivenes.location') }}</label>
                            <input type="text" name="location" id="location" class="form-control" placeholder=""
                                   value="{{ $item->location ?? old('location') }}" required/>
                            @error('location')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4  @error('long') is-invalid @enderror">
                            <label class="form-label" for="long">{{ __('effectivenes.long') }}</label>
                            <input type="text" name="long" id="long" class="form-control" placeholder=""
                                   value="{{ $item->long ?? old('long') }}" required/>
                            @error('long')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('lat') is-invalid @enderror">
                            <label class="form-label" for="lat">{{ __('effectivenes.lat') }}</label>
                            <input type="text" name="lat" id="lat" class="form-control" placeholder=""
                                   value="{{ $item->lat ?? old('lat') }}" required/>
                            @error('lat')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4  @error('price') is-invalid @enderror">
                            <label class="form-label" for="price">{{ __('effectivenes.price') }}</label>
                            <input type="number" name="price" id="price" class="form-control" placeholder=""
                                   value="{{ $item->price ?? old('price') }}" required/>
                            @error('price')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4  @error('people') is-invalid @enderror">
                            <label class="form-label" for="people">{{ __('effectivenes.people') }}</label>
                            <input type="number" name="people" id="people" class="form-control" placeholder=""
                                   value="{{ $item->people ?? old('people') }}" required/>
                            @error('people')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                         <div class="mb-1 col-md-4  @error('city_id') is-invalid @enderror">
                            <label class="form-label" for="city_id">{{ __('effectivenes.city') }}</label>
                            <select name="city_id" id="city_id" class="form-control ajax_select2 extra_field"
                                    data-ajax--url="{{ route('admin.cities.select') }}"
                                    data-ajax--cache="true">
                                @isset($item->city)
                                    <option value="{{ $item->city->id }}" selected>{{ $item->city->title }}</option>
                                @endisset
                            </select>
                            @error('city_id')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                       
                         <div class="mb-1 col-md-2  @error('free_cancelation') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="free_cancelation"
                                        value="1" id="free_cancelation"
                                        @checked($item->free_cancelation ?? false )/>
                                <label class="form-check-label" for="free_cancelation">{{ __('effectivenes.free_cancelation') }}</label>
                            </div>
                            @error('free_cancelation')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                         <div class="mb-1 col-md-2  @error('active') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active"
                                        value="1" id="active"
                                        @checked($item->active ?? false )/>
                                <label class="form-check-label" for="active">{{ __('effectivenes.active') }}</label>
                            </div>
                            @error('active')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                         <div class="mb-1 col-md-2  @error('pay_later') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="pay_later"
                                        value="1" id="pay_later"
                                        @checked($item->pay_later ?? false )/>
                                <label class="form-check-label" for="pay_later">{{ __('effectivenes.pay_later') }}</label>
                            </div>
                            @error('pay_later')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-12  @error('description_en') is-invalid @enderror">
                            <label class="form-label" for="description_en">{{ __('effectivenes.description_en') }}</label>
                            <textarea type="text" name="description_en" id="description_en" class="form-control " placeholder="">{{ $item->description_en ?? old('description_en') }}</textarea>
                            @error('description_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-12  @error('description_ar') is-invalid @enderror">
                            <label class="form-label" for="description_ar">{{ __('effectivenes.description_ar') }}</label>
                            <textarea type="text" name="description_ar" id="description_ar" class="form-control " placeholder="">{{ $item->description_ar ?? old('description_ar') }}</textarea>
                            @error('description_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4 @error('image') is-invalid @enderror">
                            <label class="form-label" for="image">{{ __('effectivenes.file') }}</label>
                            <input type="file" class="form-control input" name="cover" id="image">
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

                        <div class="card" style="border: 1px solid;padding: 20px;">
                            <h3 class="price">{{ __('effectivenes.images') }}</h3>
                            <div class="content-body">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="field" >
                                                <input type="file" id="files" name="images[]" multiple />
                                                @if (isset($item)&& $item->attachments)
                                                    @foreach ($item->attachments as $image)
                                                    <span class="pip">
                                                        <input type="hidden" name="editimages[]" value="{{ $image->id }}" />
                                                        <img class="imageThumb" src="{{ $image->file }}" title="" />
                                                        <br/><span class="remove"><i data-feather="trash" class="font-medium-2"></i></span>
                                                    </span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop
@push('scripts')
<script>
        $(window).on('load', function(){
            $(".remove").click(function(){
                $(this).parent(".pip").remove();
            });
            $(document).ready(function() {
                if (window.File && window.FileList && window.FileReader) {
                    $("#files").on("change", function(e) {
                    var files = e.target.files,
                        filesLength = files.length;
                    for (var i = 0; i < filesLength; i++) {
                        var f = files[i]
                        var fileReader = new FileReader();
                        fileReader.onload = (function(e) {
                        var file = e.target;
                        $("<span class=\"pip\">" +
                            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" />" +
                            "<br/><span class=\"remove\">Remove image</span>" +
                            "</span>").insertAfter("#files");
                        });
                        fileReader.readAsDataURL(f);
                    }
                    console.log(files);
                    });
                } else {
                    alert("Your browser doesn't support to File API")
                }
            });
        });
</script>
@endpush

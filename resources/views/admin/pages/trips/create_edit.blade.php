@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('trips.plural') }}</title>
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
          action="{{ isset($item) ? route('admin.trips.update', $item->id) : route('admin.trips.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('trips.actions.edit') : __('trips.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('trips.actions.save') }}</span>
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
                            <label class="form-label" for="title_en">{{ __('trips.name_en') }}</label>
                            <input type="text" name="title_en" id="title_en" class="form-control" placeholder=""
                                   value="{{ $item->title_en ?? old('title_en') }}" required/>
                            @error('title_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('title_ar') is-invalid @enderror">
                            <label class="form-label" for="title_ar">{{ __('trips.name_ar') }}</label>
                            <input type="text" name="title_ar" id="title_ar" class="form-control" placeholder=""
                                   value="{{ $item->title_ar ?? old('title_ar') }}" required/>
                            @error('title_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('start_point_en') is-invalid @enderror">
                            <label class="form-label" for="start_point_en">{{ __('trips.start_point_en') }}</label>
                            <input type="text" name="start_point_en" id="start_point_en" class="form-control" placeholder=""
                                   value="{{ $item->start_point_en ?? old('start_point_en') }}" required/>
                            @error('start_point_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('start_point_ar') is-invalid @enderror">
                            <label class="form-label" for="start_point_ar">{{ __('trips.start_point_ar') }}</label>
                            <input type="text" name="start_point_ar" id="start_point_ar" class="form-control" placeholder=""
                                   value="{{ $item->start_point_ar ?? old('start_point_ar') }}" required/>
                            @error('start_point_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('end_point_en') is-invalid @enderror">
                            <label class="form-label" for="end_point_en">{{ __('trips.end_point_en') }}</label>
                            <input type="text" name="end_point_en" id="end_point_en" class="form-control" placeholder=""
                                   value="{{ $item->end_point_en ?? old('end_point_en') }}" required/>
                            @error('end_point_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('end_point_ar') is-invalid @enderror">
                            <label class="form-label" for="end_point_ar">{{ __('trips.end_point_ar') }}</label>
                            <input type="text" name="end_point_ar" id="end_point_ar" class="form-control" placeholder=""
                                   value="{{ $item->end_point_ar ?? old('end_point_ar') }}" required/>
                            @error('end_point_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('program_time_en') is-invalid @enderror">
                            <label class="form-label" for="program_time_en">{{ __('trips.program_time_en') }}</label>
                            <input type="text" name="program_time_en" id="program_time_en" class="form-control" placeholder=""
                                   value="{{ $item->program_time_en ?? old('program_time_en') }}" required/>
                            @error('program_time_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('program_time_ar') is-invalid @enderror">
                            <label class="form-label" for="program_time_ar">{{ __('trips.program_time_ar') }}</label>
                            <input type="text" name="program_time_ar" id="program_time_ar" class="form-control" placeholder=""
                                   value="{{ $item->program_time_ar ?? old('program_time_ar') }}" required/>
                            @error('program_time_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('price') is-invalid @enderror">
                            <label class="form-label" for="price">{{ __('trips.price') }}</label>
                            <input type="number" name="price" id="price" class="form-control" placeholder=""
                                   value="{{ $item->price ?? old('price') }}" required/>
                            @error('price')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <pre>

                        'steps_list',
                        </pre>
                        <div class="mb-1 col-md-4  @error('people') is-invalid @enderror">
                            <label class="form-label" for="people">{{ __('trips.people') }}</label>
                            <input type="number" name="people" id="people" class="form-control" placeholder=""
                                   value="{{ $item->people ?? old('people') }}" />
                            @error('people')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('start_long') is-invalid @enderror">
                            <label class="form-label" for="start_long">{{ __('trips.start_long') }}</label>
                            <input type="number" name="start_long" id="start_long" class="form-control" placeholder=""
                                   value="{{ $item->start_long ?? old('start_long') }}" />
                            @error('start_long')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('start_lat') is-invalid @enderror">
                            <label class="form-label" for="start_lat">{{ __('trips.start_lat') }}</label>
                            <input type="number" name="start_lat" id="start_lat" class="form-control" placeholder=""
                                   value="{{ $item->start_lat ?? old('start_lat') }}" />
                            @error('start_lat')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('end_long') is-invalid @enderror">
                            <label class="form-label" for="end_long">{{ __('trips.end_long') }}</label>
                            <input type="number" name="end_long" id="end_long" class="form-control" placeholder=""
                                   value="{{ $item->end_long ?? old('end_long') }}" />
                            @error('end_long')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('end_lat') is-invalid @enderror">
                            <label class="form-label" for="end_lat">{{ __('trips.end_lat') }}</label>
                            <input type="number" name="end_lat" id="end_lat" class="form-control" placeholder=""
                                   value="{{ $item->end_lat ?? old('end_lat') }}" />
                            @error('end_lat')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                         <div class="mb-1 col-md-4  @error('city_id') is-invalid @enderror">
                            <label class="form-label" for="city_id">{{ __('trips.city') }}</label>
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
                         <div class="mb-1 col-md-4  @error('requirement_ids') is-invalid @enderror">
                            <label class="form-label" for="requirement_ids">{{ __('trips.requirements') }}</label>
                            <select name="requirement_ids[]" id="requirement_ids" class="form-control ajax_select2 extra_field"
                                    data-ajax--url="{{ route('admin.requirements.select') }}"
                                    data-ajax--cache="true" multiple>
                                @isset($item->requirement_ids)
                                @foreach ($item->requirements as $requirement)

                                <option value="{{ $requirement->id }}" selected>{{ $requirement->title }}</option>
                                @endforeach
                                @endisset
                            </select>
                            @error('requirement_ids')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                         <div class="mb-1 col-md-4  @error('sub_category_ids') is-invalid @enderror">
                            <label class="form-label" for="sub_category_ids">{{ __('trips.sub_categories') }}</label>
                            <select name="sub_category_ids[]" id="sub_category_ids" class="form-control ajax_select2 extra_field"
                                    data-ajax--url="{{ route('admin.sub_categories.select') }}"
                                    data-ajax--cache="true" multiple>
                                @isset($item->subcategory)
                                @foreach ($item->subcategory as $sub_category)

                                <option value="{{ $sub_category->id }}" selected>{{ $sub_category->title }}</option>
                                @endforeach
                                @endisset
                            </select>
                            @error('sub_category_ids')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                         <div class="mb-1 col-md-4  @error('featur_ids') is-invalid @enderror">
                            <label class="form-label" for="featur_ids">{{ __('trips.features') }}</label>
                            <select name="featur_ids[]" id="featur_ids" class="form-control ajax_select2 extra_field"
                                    data-ajax--url="{{ route('admin.features.select') }}"
                                    data-ajax--cache="true" multiple>
                                @isset($item->features)
                                @foreach ($item->features as $feature)

                                <option value="{{ $feature->id }}" selected>{{ $feature->title }}</option>
                                @endforeach
                                @endisset
                            </select>
                            @error('featur_ids')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                         <div class="mb-1 col-md-2  @error('free_cancelation') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="free_cancelation"
                                        value="1" id="free_cancelation"
                                        @checked($item->free_cancelation ?? false )/>
                                <label class="form-check-label" for="free_cancelation">{{ __('trips.free_cancelation') }}</label>
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
                                <label class="form-check-label" for="active">{{ __('trips.active') }}</label>
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
                                <label class="form-check-label" for="pay_later">{{ __('trips.pay_later') }}</label>
                            </div>
                            @error('pay_later')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-12  @error('steps_list') is-invalid @enderror">
                            <label class="form-label" for="steps_list">{{ __('trips.steps_list') }}</label>
                            <div id="steps_list_wrapper">
                                @if(isset($item) && $item->steps_list)
                                    @foreach($item->steps_list as $step)
                                        <div class="row mb-2">
                                            <div class="col-md-10">
                                                <input type="text" name="steps_list[]" class="form-control" value="{{ $step }}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger remove-step">-</button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row mb-2">
                                        <div class="col-md-10">
                                            <input type="text" name="steps_list[]" class="form-control" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-step">-</button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-primary" id="add_step">{{ __('trips.add_step') }}</button>
                            @error('steps_list')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-12  @error('available_times') is-invalid @enderror">
                            <label class="form-label" for="available_times">{{ __('trips.available_times') }}</label>
                            <div id="available_times_wrapper">
                                @if(isset($item) && $item->available_times)
                                    @foreach($item->available_times as $time)
                                        <div class="row mb-2">
                                            <div class="col-md-5">
                                                <input type="time" name="available_times[from_time][]" class="form-control" value="{{ $time['from_time'] }}" required>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="time" name="available_times[to_time][]" class="form-control" value="{{ $time['to_time'] }}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger remove-time">-</button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row mb-2">
                                        <div class="col-md-5">
                                            <input type="time" name="available_times[from_time][]" class="form-control" required>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="time" name="available_times[to_time][]" class="form-control" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-time">-</button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-primary" id="add_time">{{ __('trips.add_time') }}</button>
                            @error('available_times')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>


                           <div class="mb-1 col-md-12  @error('description_en') is-invalid @enderror">
                            <label class="form-label" for="description_en">{{ __('trips.description_en') }}</label>
                            <textarea type="text" name="description_en" id="description_en" class="form-control editor" placeholder="">{{ $item->description_en ?? old('description_en') }}</textarea>
                            @error('description_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-12  @error('description_ar') is-invalid @enderror">
                            <label class="form-label" for="description_ar">{{ __('trips.description_ar') }}</label>
                            <textarea type="text" name="description_ar" id="description_ar" class="form-control editor" placeholder="">{{ $item->description_ar ?? old('description_ar') }}</textarea>
                            @error('description_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4 @error('image') is-invalid @enderror">
                            <label class="form-label" for="image">{{ __('trips.file') }}</label>
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
                            <h3 class="price">{{ __('trips.available_days') }}</h3>
                            <div class="content-body">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            @php
                                                $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                                            @endphp
                                            @foreach($days as $index => $day)
                                                <div class="mb-1 col-md-2 @error('available_days') is-invalid @enderror">
                                                    <br>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="available_days[{{ $index }}]"
                                                               value="{{ $day }}" id="available_days_{{ $day }}"
                                                               @checked(in_array($day, $item->available_days ?? [])) />
                                                        <label class="form-check-label" for="available_days_{{ $day }}">{{ __('trips.' . $day) }}</label>
                                                    </div>
                                                    @error('available_days')
                                                    <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card" style="border: 1px solid;padding: 20px;">
                            <h3 class="price">{{ __('trips.images') }}</h3>
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
                                                        <img class="imageThumb" src="{{ $image->photo }}" title="" />
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
        $(document).ready(function() {
        $('#add_time').click(function() {
            $('#available_times_wrapper').append(`
                <div class="row mb-2">
                    <div class="col-md-5">
                        <input type="time" name="available_times[from_time][]" class="form-control" required>
                    </div>
                    <div class="col-md-5">
                        <input type="time" name="available_times[to_time][]" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-time">-</button>
                    </div>
                </div>
            `);
        });

        $(document).on('click', '.remove-time', function() {
            $(this).closest('.row').remove();
        });
    });
    $(document).ready(function() {
        $('#add_step').click(function() {
            $('#steps_list_wrapper').append(`
                <div class="row mb-2">
                    <div class="col-md-10">
                        <input type="text" name="steps_list[]" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-step">-</button>
                    </div>
                </div>
            `);
        });

        $(document).on('click', '.remove-step', function() {
            $(this).closest('.row').remove();
        });
    });
</script>
@endpush

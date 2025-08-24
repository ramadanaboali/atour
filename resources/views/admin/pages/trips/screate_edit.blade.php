@extends('admin.layouts.master')

@section('title')
<title>{{ config('app.name') }} | {{ __('trips.plural') }}</title>
@endsection

@push('styles')
<style>
    .section-label {
        position: absolute;
        top: -12px;
        background: #fff;
        padding: 0 8px;
        font-weight: bold;
        color: #495057;
        border-radius: 5px;
    }

    input[type="file"] {
        display: block;
    }

    .imageThumb {
        max-height: 75px;
        border: 2px solid #ccc;
        padding: 2px;
        border-radius: 6px;
        cursor: pointer;
    }

    .pip {
        display: inline-block;
        margin: 10px 10px 0 0;
        position: relative;
    }

    .pip .remove {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        padding: 3px 6px;
        font-size: 12px;
        cursor: pointer;
    }

    .pip .remove:hover {
        background: #b52a36;
    }

</style>
@endpush

@section('content')
<form method="post" enctype="multipart/form-data" id="trip-form" action="{{ isset($item) ? route('admin.trips.update', $item->id) : route('admin.trips.store') }}">
    @csrf
    @method(isset($item) ? 'PUT' : 'POST')

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 text-dark fw-bold">
            <i data-feather="map-pin"></i>
            {{ isset($item) ? __('trips.actions.edit') : __('trips.actions.create') }}
        </h1>
        <button class="btn btn-primary">
            <i data-feather="save"></i> {{ __('trips.actions.save') }}
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body row g-3">
            @php
            $availableLangs = config('languages.available');
            $translatableFields = [
            'title' => __('trips.title'),
            'start_point' => __('trips.start_point'),
            'end_point' => __('trips.end_point'),
            'program_time' => __('trips.program_time'),
            'description' => __('trips.description'),
            ];
            $translations = old('translations', $item->translations ?? []);
            @endphp

            <!-- Translatable Fields -->
            <!-- Translations Section -->
            <div class="col-12">
                <h5 class="mb-3">{{ __('admin.translations') }}</h5>

                <!-- Existing translations -->
                <div id="translations-wrapper">

                    @php
                    $usedLangs = [];
                    $translations = old('translations', $item->translations ?? []);
                    @endphp

                    @foreach($translations as $index => $t)
                    @php $usedLangs[] = $t['locale']; @endphp
                    <div class="translation-row input-group mb-1" data-locale="{{ $t['locale'] }}">
                        <span class="input-group-text">{{ config('languages.available')[$t['locale']] ?? strtoupper($t['locale']) }}</span>
                        <input type="hidden" name="translations[{{ $index }}][locale]" value="{{ $t['locale'] }}">
                        <input type="text" name="translations[{{ $index }}][title]" class="form-control" value="{{ $t['title'] ?? '' }}" placeholder="{{ __('Title') }}">
                        <button type="button" class="btn btn-danger remove-translation">-</button>
                    </div>

                    @endforeach
                </div>

                <!-- Add translation button -->
                <div class="input-group mt-2" id="add-translation-group">
                    <select id="language-select" class="form-select" style="max-width:200px;">
                        <option value="">{{ __('admin.Select Language') }}</option>
                        @foreach($availableLangs as $code => $name)
                        <option value="{{ $code }}" @if(in_array($code, $usedLangs)) disabled @endif>
                            {{ __('admin.'.$name) }}
                        </option>
                        @endforeach
                    </select>
                    <button type="button" id="add-translation" class="btn btn-sm btn-primary">
                        + {{ __('admin.Add Language') }}
                    </button>
                </div>
            </div>



            <div class="mb-1 col-md-4  @error('price') is-invalid @enderror">
                <label class="form-label" for="price">{{ __('trips.price') }}</label>
                <input type="number" name="price" id="price" class="form-control" placeholder="" value="{{ $item->price ?? old('price') }}" required />
                @error('price')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-1 col-md-4  @error('people') is-invalid @enderror">
                <label class="form-label" for="people">{{ __('trips.people') }}</label>
                <input type="number" name="people" id="people" class="form-control" placeholder="" value="{{ $item->people ?? old('people') }}" />
                @error('people')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-1 col-md-4  @error('start_long') is-invalid @enderror">
                <label class="form-label" for="start_long">{{ __('trips.start_long') }}</label>
                <input type="number" name="start_long" id="start_long" class="form-control" placeholder="" value="{{ $item->start_long ?? old('start_long') }}" />
                @error('start_long')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-1 col-md-4  @error('start_lat') is-invalid @enderror">
                <label class="form-label" for="start_lat">{{ __('trips.start_lat') }}</label>
                <input type="number" name="start_lat" id="start_lat" class="form-control" placeholder="" value="{{ $item->start_lat ?? old('start_lat') }}" />
                @error('start_lat')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-1 col-md-4  @error('end_long') is-invalid @enderror">
                <label class="form-label" for="end_long">{{ __('trips.end_long') }}</label>
                <input type="number" name="end_long" id="end_long" class="form-control" placeholder="" value="{{ $item->end_long ?? old('end_long') }}" />
                @error('end_long')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-1 col-md-4  @error('end_lat') is-invalid @enderror">
                <label class="form-label" for="end_lat">{{ __('trips.end_lat') }}</label>
                <input type="number" name="end_lat" id="end_lat" class="form-control" placeholder="" value="{{ $item->end_lat ?? old('end_lat') }}" />
                @error('end_lat')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>


            <!-- Location -->
            <div class="col-md-4">
                <label class="form-label">{{ __('trips.city') }}</label>
                <select name="city_id" class="form-control ajax_select2" data-ajax--url="{{ route('admin.cities.select') }}">
                    @isset($item->city)
                    <option value="{{ $item->city->id }}" selected>{{ $item->city->title }}</option>
                    @endisset
                </select>
            </div>

            <!-- Requirements, Subcategories, Features -->
            <div class="col-md-4">
                <label class="form-label">{{ __('trips.requirements') }}</label>
                <select name="requirement_ids[]" class="form-control ajax_select2" multiple data-ajax--url="{{ route('admin.requirements.select') }}">
                    @isset($item->requirements)
                    @foreach($item->requirements as $req)
                    <option value="{{ $req->id }}" selected>{{ $req->title }}</option>
                    @endforeach
                    @endisset
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('trips.sub_categories') }}</label>
                <select name="sub_category_ids[]" class="form-control ajax_select2" multiple data-ajax--url="{{ route('admin.sub_categories.select') }}">
                    @isset($item->subcategory)
                    @foreach($item->subcategory as $sub)
                    <option value="{{ $sub->id }}" selected>{{ $sub->title }}</option>
                    @endforeach
                    @endisset
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('trips.features') }}</label>
                <select name="feature_ids[]" class="form-control ajax_select2" multiple data-ajax--url="{{ route('admin.features.select') }}">
                    @isset($item->features)
                    @foreach($item->features as $feat)
                    <option value="{{ $feat->id }}" selected>{{ $feat->title }}</option>
                    @endforeach
                    @endisset
                </select>
            </div>

            <!-- Checkboxes -->
            <div class="col-md-2 form-check mt-4">
                <input class="form-check-input" type="checkbox" name="free_cancelation" value="1" id="free_cancelation" @checked($item->free_cancelation ?? false)>
                <label class="form-check-label" for="free_cancelation">{{ __('trips.free_cancelation') }}</label>
            </div>
            <div class="col-md-2 form-check mt-4">
                <input class="form-check-input" type="checkbox" name="active" value="1" id="active" @checked($item->active ?? false)>
                <label class="form-check-label" for="active">{{ __('trips.active') }}</label>
            </div>
            <div class="col-md-2 form-check mt-4">
                <input class="form-check-input" type="checkbox" name="pay_later" value="1" id="pay_later" @checked($item->pay_later ?? false)>
                <label class="form-check-label" for="pay_later">{{ __('trips.pay_later') }}</label>
            </div>

            <!-- Steps -->
            <div class="col-12">
                <label class="form-label">{{ __('trips.steps_list') }}</label>
                <div id="steps_list_wrapper">
                    @forelse($item->steps_list ?? [''] as $step)
                    <div class="d-flex mb-2">
                        <input type="text" name="steps_list[]" class="form-control me-2" value="{{ $step }}">
                        <button type="button" class="btn btn-danger remove-step">-</button>
                    </div>
                    @empty
                    <div class="d-flex mb-2">
                        <input type="text" name="steps_list[]" class="form-control me-2">
                        <button type="button" class="btn btn-danger remove-step">-</button>
                    </div>
                    @endforelse
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" id="add_step">+ {{ __('trips.add_step') }}</button>
            </div>

            <!-- Available Times -->
            <div class="col-12">
                <label class="form-label">{{ __('trips.available_times') }}</label>
                <div id="available_times_wrapper">
                    @forelse($item->available_times ?? [['from_time'=>'','to_time'=>'']] as $time)
                    <div class="row g-2 mb-2">
                        <div class="col-md-5">
                            <input type="time" name="available_times[from_time][]" class="form-control" value="{{ $time['from_time'] }}">
                        </div>
                        <div class="col-md-5">
                            <input type="time" name="available_times[to_time][]" class="form-control" value="{{ $time['to_time'] }}">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-time">-</button>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" id="add_time">+ {{ __('trips.add_time') }}</button>
            </div>

            <!-- Images -->
            <div class="col-12">
                <label class="form-label">{{ __('trips.images') }}</label>
                <input type="file" name="images[]" id="files" multiple class="form-control">
                <div class="mt-2">
                    @if(isset($item) && $item->attachments)
                    @foreach($item->attachments as $image)
                    <span class="pip">
                        <input type="hidden" name="editimages[]" value="{{ $image->id }}">
                        <img src="{{ $image->file }}" class="imageThumb">
                        <span class="remove">&times;</span>
                    </span>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')



<script>
    $(document).on('click', '.remove', function() {
        $(this).parent('.pip').remove();
    });
    $('#files').on('change', function(e) {
        let files = e.target.files;
        for (let i = 0; i < files.length; i++) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $("<span class='pip'><img class='imageThumb' src='" + e.target.result + "'><span class='remove'>&times;</span></span>").insertAfter("#files");
            }
            reader.readAsDataURL(files[i]);
        }
    });
    $('#add_time').click(function() {
        $('#available_times_wrapper').append(`
        <div class="row g-2 mb-2">
            <div class="col-md-5"><input type="time" name="available_times[from_time][]" class="form-control"></div>
            <div class="col-md-5"><input type="time" name="available_times[to_time][]" class="form-control"></div>
            <div class="col-md-2"><button type="button" class="btn btn-danger remove-time">-</button></div>
        </div>`);
    });
    $(document).on('click', '.remove-time', function() {
        $(this).closest('.row').remove();
    });
    $('#add_step').click(function() {
        $('#steps_list_wrapper').append(`
        <div class="d-flex mb-2">
            <input type="text" name="steps_list[]" class="form-control me-2">
            <button type="button" class="btn btn-danger remove-step">-</button>
        </div>`);
    });
    $(document).on('click', '.remove-step', function() {
        $(this).closest('.d-flex').remove();
    });

    let translations = @json(__('scripts'));
    const availableLangs = @json(config('languages.available'));

    function getLangLabel(lang) {
        return translations[lang] ? translations[lang] : lang;
    }




    document.getElementById('add-translation').addEventListener('click', function() {
        let select = document.getElementById('language-select');
        let lang = select.value;
        if (!lang) return;

        // Prevent adding same language twice
        if (document.querySelector('.translation-row[data-locale="' + lang + '"]')) {
            alert('Language already added.');
            return;
        }

        let wrapper = document.getElementById('translations-wrapper');
        let index = wrapper.querySelectorAll('.translation-row').length;

        let div = document.createElement('div');
        div.setAttribute('data-locale', lang);
        // Translate language name using translation key if available
        let langLabel = getLangLabel(availableLangs[lang]);
        div.innerHTML = `<div class="border rounded p-1 mb-12 bg-light">
            <h6 class="fw-bold mb-2">${langLabel}</h6>


            <input type="hidden" name="translations[${index}][locale]" value="${lang}">
            <div class="mb-2">
                <label class="form-label">{{ __('trips.title') }}</label>
                <input type="text" class="form-control" name="translations[${index}][title]" value="" placeholder="{{ __('trips.title') }}">
            </div>
            <div class="mb-2">
                <label class="form-label">{{ __('trips.start_point') }}</label>
                <input type="text" class="form-control" name="translations[${index}][start_point]" value="" placeholder="{{ __('trips.start_point') }}">
            </div>
            <div class="mb-2">
                <label class="form-label">{{ __('trips.end_point') }}</label>
                <input type="text" class="form-control" name="translations[${index}][end_point]" value="" placeholder="{{ __('trips.end_point') }}">
            </div>
            <div class="mb-2">
                <label class="form-label">{{ __('trips.program_time') }}</label>
                <input type="text" class="form-control" name="translations[${index}][program_time]" value="" placeholder="{{ __('trips.program_time') }}">
            </div>
            <div class="mb-2">
                <label class="form-label">{{ __('trips.description') }}</label>
                <textarea class="form-control" name="translations[${index}][description]" placeholder="{{ __('trips.description') }}"></textarea>
            </div>
            <button type="button" class="btn btn-danger remove-translation">-</button>

        </div>`;


        wrapper.appendChild(div);

        // Disable added language in dropdown
        select.querySelector('option[value="' + lang + '"]').disabled = true;
        select.value = '';
    });

    // Handle removal
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-translation')) {
            let row = e.target.closest('.translation-row');
            let lang = row.getAttribute('data-locale');

            // Re-enable language in dropdown
            let select = document.getElementById('language-select');
            let option = select.querySelector('option[value="' + lang + '"]');
            if (option) option.disabled = false;

            row.remove();
        }
    });

</script>

@endpush

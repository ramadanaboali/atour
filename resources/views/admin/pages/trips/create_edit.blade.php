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

            $translations = old('translations', $item->translations ?? []);
            @endphp

            <!-- Translatable Fields -->
            <!-- Translations Section -->
            <div class="col-12">

                <div id="translations-wrapper">
                    @php
                    $usedLangs = [];
                    $translations = old('translations', $item->translations ?? []);
                    @endphp
                    @foreach($translations as $index => $t)
                    @php $usedLangs[] = $t['locale']; @endphp
                    <div class="border rounded p-1 row bg-light translation-row" data-locale="{{ $t['locale'] ?? $t->locale }}">
                        <!-- make language label as badge -->
                        {{-- add span and button on design --}}
                        <div class="col-md-12 d-flex align-items-center ">
                            <span class="badge bg-secondary me-2" style="font-size: 1rem; padding: 0.5em 1em;">

                                {{ __('admin.' . (config('languages.available')[$t['locale']] ?? strtoupper($t['locale']))) }}
                            </span>
                            <div class="ms-auto">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-translation" style="padding: 0.25em 0.75em;">
                                    <i data-feather="x"></i> {{ __('admin.Remove') }}
                                </button>
                            </div>
                            <input type="hidden" name="translations[{{ $index }}][locale]" value="{{ $t['locale'] }}">
                        </div>
                        <hr>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('trips.title') }}</label>
                            <input type="text" class="form-control" name="translations[{{ $index }}][title]" value="{{ $t['title'] ?? '' }}" placeholder="{{ __('trips.title') }}">

                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('trips.start_point') }}</label>
                            <input type="text" class="form-control" name="translations[{{ $index }}][start_point]" value="{{ $t['start_point'] ?? '' }}" placeholder="{{ __('trips.start_point') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('trips.end_point') }}</label>
                            <input type="text" class="form-control" name="translations[{{ $index }}][end_point]" value="{{ $t['end_point'] ?? '' }}" placeholder="{{ __('trips.end_point') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('trips.program_time') }}</label>
                            <input type="text" class="form-control" name="translations[{{ $index }}][program_time]" value="{{ $t['program_time'] ?? '' }}" placeholder="{{ __('trips.program_time') }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">{{ __('trips.description') }}</label>
                            <textarea class="form-control" name="translations[{{ $index }}][description]" placeholder="{{ __('trips.description') }}">{{ $t['description'] ?? '' }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <div id="steps_list_wrapper_{{ $index }}">
                                <label class="form-label">{{ __('trips.steps_list') }}</label>
                                @forelse($t['steps_list'][$t['locale']] ?? [''] as $step)

                                <div class="d-flex mb-2">
                                    <input type="text" name="translations[{{ $index }}][steps_list][{{ $t['locale'] }}][]" class="form-control me-2" value="{{ $step ?? '' }}">
                                    <button type="button" class="btn btn-danger remove-step">-</button>
                                </div>
                                @empty
                                <div class="d-flex mb-2">
                                    <input type="text" name="translations[{{ $index }}][steps_list][{{ $t['locale'] }}][]" class="form-control me-2" value="">

                                    <button type="button" class="btn btn-danger remove-step">-</button>
                                </div>
                                @endforelse
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm add_step" data-index="{{ $index }}" data-lang="{{ $t['locale'] }}">+ {{ __('trips.add_step') }}</button>
                        </div>

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
                <select name="featur_ids[]" class="form-control ajax_select2" multiple data-ajax--url="{{ route('admin.features.select') }}">

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
                                        <input class="form-check-input" type="checkbox" name="available_days[{{ $index }}]" value="{{ $day }}" id="available_days_{{ $day }}" @checked(in_array($day, $item->available_days ?? [])) />
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
    $('.add_step').click(function() {
        var index_n = $(this).data('index');
        var lang = $(this).data('lang');
        $('#steps_list_wrapper_' + index_n).append(`

        <div class="d-flex mb-2">
            <input type="text" name="translations[${index_n}][steps_list][${lang}][]" class="form-control me-2" value="">

            <button type="button" class="btn btn-danger remove-step">-</button>
        </div>

       `);
    });
    $(document).on('click', '.remove-step', function() {
        $(this).closest('.d-flex').remove();
    });

    let translations = @json(__('scripts'));
    const availableLangs = @json(config('languages.available'));

    function getLangLabel(lang) {
        return translations[lang] ?? lang;
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

        // let div = document.createElement('div');
        let langLabel = getLangLabel(availableLangs[lang]);
        wrapper.innerHTML += `<div class="border rounded p-1 row bg-light translation-row" data-locale="${lang}">

                            <div class="col-md-12 d-flex align-items-center ">
                                <span class="badge bg-secondary me-2" style="font-size: 1rem; padding: 0.5em 1em;">${langLabel}</span>
                                <div class="ms-auto">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-translation" style="padding: 0.25em 0.75em;">
                                        <i data-feather="x"></i> {{ __('admin.Remove') }}
                                    </button>
                                </div>
                                <input type="hidden" name="translations[${index}][locale]" value="${lang}">
                            </div>
                            <hr>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('trips.title') }}</label>
                                <input type="text" class="form-control" name="translations[${index}][title]" value="" placeholder="{{ __('trips.title') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('trips.start_point') }}</label>
                                <input type="text" class="form-control" name="translations[${index}][start_point]" value="" placeholder="{{ __('trips.start_point') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('trips.end_point') }}</label>
                                <input type="text" class="form-control" name="translations[${index}][end_point]" value="" placeholder="{{ __('trips.end_point') }}">
                            </div>
                            <div class="col-md-4">

                                <label class="form-label">{{ __('trips.program_time') }}</label>
                                <input type="text" class="form-control" name="translations[${index}][program_time]" value="" placeholder="{{ __('trips.program_time') }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">{{ __('trips.description') }}</label>
                                <textarea class="form-control" name="translations[${index}][description]" placeholder="{{ __('trips.description') }}"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{ __('trips.steps_list') }}</label>
                                <div id="steps_list_wrapper_${index}">
                                    <div class="d-flex mb-2">
                                        <input type="text" name="translations[${index}][steps_list][${lang}][]" class="form-control me-2" value="">
                                        <button type="button" class="btn btn-danger remove-step">-</button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm add_step" data-index="${index}" data-lang="${lang}">+ {{ __('trips.add_step') }}</button>
                            </div>
                        </div>`;


        // wrapper.appendChild(innerHTML);

        // Disable added language in dropdown
        select.querySelector('option[value="' + lang + '"]').disabled = true;
        select.value = '';
    });

    // Handle removal
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-translation');
        if (btn) {
            let row = btn.closest('.translation-row');
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

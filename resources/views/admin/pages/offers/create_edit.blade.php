@extends('admin.layouts.master')
@section('title')
<title>{{ config('app.name') }} | {{ __('offers.plural') }}</title>
@endsection
@section('content')
<form method='post' enctype="multipart/form-data" id="jquery-val-form" action="{{ isset($item) ? route('admin.offers.update', $item->id) : route('admin.offers.store') }}">
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

                    @php
                    $availableLangs = config('languages.available');

                    $translations = old('translations', $item->translations ?? []);
                    @endphp

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
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('gifts.title') }}</label>
                                    <input type="text" class="form-control" name="translations[{{ $index }}][title]" value="{{ $t['title'] ?? '' }}" placeholder="{{ __('gifts.title') }}">

                                </div>


                                <div class="col-md-6">
                                    <label class="form-label">{{ __('gifts.description') }}</label>
                                    <textarea class="form-control" name="translations[{{ $index }}][description]" placeholder="{{ __('gifts.description') }}">{{ $t['description'] ?? '' }}</textarea>
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


                    <div class="mb-1 col-md-4  @error('vendor_id') is-invalid @enderror">
                        <label class="form-label" for="vendor_id">{{ __('offers.supplier') }}</label>
                        <select name="vendor_id" id="vendor_id" class="form-control ajax_select2 extra_field" data-ajax--url="{{ route('admin.suppliers.select') }}" onchange="updateAjaxUrl()" data-ajax--cache="false">
                            @isset($item->vendor)
                            <option value="{{ $item->vendor_id }}" selected>{{ $item->vendor?->name }}</option>
                            @endisset
                        </select>
                        @error('vendor_id')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-1 col-md-4  @error('type') is-invalid @enderror">
                        <label class="form-label" for="type">{{ __('offers.type') }}</label>
                        <select name="type" id="type" class="form-control " onchange="updateAjaxUrl()">
                            <option value="trip" @isset($item->type) {{ $item->type=="trip"?'selected':'' }} @endisset>{{ __('offers.types.trip') }}</option>
                            <option value="gift" @isset($item->type) {{ $item->type=="gift"?'selected':'' }} @endisset>{{ __('offers.types.gift') }}</option>
                            <option value="effectivenes" @isset($item->type) {{ $item->type=="effectivenes"?'selected':'' }} @endisset>{{ __('offers.types.effectivenes') }}</option>
                        </select>
                        @error('type')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-1 col-md-4  @error('trip_id') is-invalid @enderror" id="trip_section" style="display: none">
                        <label class="form-label" for="trip_id">{{ __('offers.types.trip') }}</label>
                        <select name="trip_id" id="trip_id" class="form-control ">
                        </select>
                        @error('trip_id')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-1 col-md-4  @error('gift_id') is-invalid @enderror" id="gift_section" style="display:none">
                        <label class="form-label" for="gift_id">{{ __('offers.types.gift') }}</label>
                        <select name="gift_id" id="gift_id" class="form-control ">
                        </select>
                        @error('gift_id')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-1 col-md-4  @error('effectivenes_id') is-invalid @enderror" id="effectivenes_section" style="display: none">
                        <label class="form-label" for="effectivenes_id">{{ __('offers.types.effectivenes') }}</label>
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
            type: 'POST'
            , url: "{{ route('admin.getOffers') }}"
            , data: {
                vendor_id: vendorSelect
                , type: type
                , effectivenes_id: effectivenes_id
                , trip_id: trip_id
                , gift_id: gift_id
            }
            , success: function(data) {
                if (type === "trip") {

                    $("#trip_id").html(data);
                    $("#trip_section").show();
                    $("#effectivenes_section").hide();
                    $("#gift_section").hide();
                }
                if (type === "effectivenes") {
                    $("#effectivenes_id").html(data);
                    $("#trip_section").hide();
                    $("#effectivenes_section").show();
                    $("#gift_section").hide();
                }
                if (type === "gift") {
                    $("#gift_id").html(data);
                    $("#trip_section").hide();
                    $("#effectivenes_section").hide();
                    $("#gift_section").show();
                }
            }
        });
    }
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
       <div class="col-md-6">
           <label class="form-label">{{ __('gifts.title') }}</label>
           <input type="text" class="form-control" name="translations[${index}][title]" value="" placeholder="{{ __('gifts.title') }}">
       </div>

       <div class="col-md-6">
           <label class="form-label">{{ __('gifts.description') }}</label>
           <textarea class="form-control" name="translations[${index}][description]" placeholder="{{ __('gifts.description') }}"></textarea>
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

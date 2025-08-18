@extends('admin.layouts.master')
@section('title')
<title>{{ config('app.name') }} | {{ __('countries.plural') }}</title>
@endsection
@section('content')
<form method='post' enctype="multipart/form-data" id="jquery-val-form" action="{{ isset($item) ? route('admin.countries.update', $item->id) : route('admin.countries.store') }}">
    <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
    @csrf
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-1">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="box" class="font-medium-2"></i>
                        <span>{{ isset($item) ? __('countries.actions.edit') : __('countries.actions.create') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
            <div class="mb-1 breadcrumb-right">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                        <i data-feather="save"></i>
                        <span class="active-sorting text-primary">{{ __('countries.actions.save') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="mb-1 col-md-12">
                        <label class="form-label">{{ __('countries.plural') }}</label>
                        <div id="translations-wrapper">
                            @php
                            $translations = old('translations', $item->translations ?? []);
                            $availableLangs = config('languages.available');
                            $usedLangs = collect($translations)->pluck('locale')->toArray();
                            @endphp

                            @foreach($translations as $tr)
                            <div class="translation-row input-group mb-1" data-locale="{{ $tr['locale'] ?? $tr->locale }}">
                                <span class="input-group-text">
                                    {{ __('admin.'.($availableLangs[$tr['locale'] ?? $tr->locale] ?? strtoupper($tr['locale'] ?? $tr->locale))) }}

                                </span>
                                <input type="hidden" name="translations[{{ $loop->index }}][locale]" value="{{ $tr['locale'] ?? $tr->locale }}">
                                <input type="text" name="translations[{{ $loop->index }}][title]" class="form-control" value="{{ $tr['title'] ?? $tr->title }}" placeholder="{{ __('Title') }}">
                                <button type="button" class="btn btn-danger remove-translation">-</button>
                            </div>
                            @endforeach
                        </div>

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

                    <div class="mb-1 col-md-4  @error('active') is-invalid @enderror">
                        <br>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="active" value="1" id="active" @checked($item->active ?? false )/>
                            <label class="form-check-label" for="active">{{ __('countries.active') }}</label>
                        </div>
                        @error('active')
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
    const availableLangs = @json(config('languages.available'));

    document.getElementById('add-translation').addEventListener('click', function() {
        let select = document.getElementById('language-select');
        let lang = select.value;
        if (!lang) return;

        // Prevent adding the same language twice
        if (document.querySelector('.translation-row[data-locale="' + lang + '"]')) {
            alert('Language already added.');
            return;
        }

        let wrapper = document.getElementById('translations-wrapper');
        let index = wrapper.querySelectorAll('.translation-row').length;
        let div = document.createElement('div');
        div.classList.add('translation-row', 'input-group', 'mb-1');
        div.setAttribute('data-locale', lang);
        div.innerHTML = `
            <span class="input-group-text">${availableLangs[lang] || lang.toUpperCase()}</span>
            <input type="hidden" name="translations[${index}][locale]" value="${lang}">
            <input type="text" name="translations[${index}][title]" class="form-control" placeholder="{{ __('Title') }}">
            <button type="button" class="btn btn-danger remove-translation">-</button>
        `;
        wrapper.appendChild(div);

        // Disable selected language in dropdown
        select.querySelector('option[value="' + lang + '"]').disabled = true;
        select.value = '';
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-translation')) {
            let row = e.target.closest('.translation-row');
            let lang = row.getAttribute('data-locale');
            // Enable language in dropdown again
            let select = document.getElementById('language-select');
            let option = select.querySelector('option[value="' + lang + '"]');
            if (option) option.disabled = false;
            row.remove();
        }
    });

</script>
@endpush

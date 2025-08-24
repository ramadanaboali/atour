@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('faqs.plural') }}</title>
@endsection
@section('content')
    <form method='post' enctype="multipart/form-data"  id="jquery-val-form"
          action="{{ isset($item) ? route('admin.faqs.update', $item->id) : route('admin.faqs.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('faqs.actions.edit') : __('faqs.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('faqs.actions.save') }}</span>
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
                                    <div class="col-md-12">
                                        <label class="form-label">{{ __('gifts.question') }}</label>
                                        <input type="text" class="form-control" name="translations[{{ $index }}][question]" value="{{ $t['question'] ?? '' }}" placeholder="{{ __('gifts.question') }}">

                                    </div>


                                    <div class="col-md-12">
                                        <label class="form-label">{{ __('gifts.answer') }}</label>
                                        <textarea class="form-control" name="translations[{{ $index }}][answer]" placeholder="{{ __('gifts.answer') }}">{{ $t['answer'] ?? '' }}</textarea>
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
                    </div>


                </div>
            </div>
        </div>
    </form>
@stop
@push('scripts')
<script>
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

        // let div = document.createElement('div');
        let langLabel = getLangLabel(availableLangs[lang]);
        let html = `<div class="border rounded p-1 row bg-light translation-row" data-locale="${lang}">


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
        <div class="col-md-12">
            <label class="form-label">{{ __('faqs.question') }}</label>
            <input type="text" class="form-control" name="translations[${index}][question]" value="" placeholder="{{ __('faqs.question') }}">
        </div>

        <div class="col-md-12">
            <label class="form-label">{{ __('faqs.answer') }}</label>
            <textarea class="form-control" name="translations[${index}][answer]" placeholder="{{ __('faqs.answer') }}"></textarea>
        </div>

    </div>`;
        wrapper.insertAdjacentHTML('beforeend', html);



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


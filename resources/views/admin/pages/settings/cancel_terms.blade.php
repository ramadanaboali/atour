@extends('admin.layouts.master')
@section('title')
<title>{{ config('app.name') }} | {{ __('settings.cancel_terms') }}</title>
@endsection
@section('content')
<form method='post' enctype="multipart/form-data" id="jquery-val-form" action="{{ route('admin.settings.updateTerm') }}">
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="type" value="cancel_terms">
    @csrf
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="box" class="font-medium-2"></i>
                        <span>{{ __('settings.cancel_terms') }}</span>
                    </h1>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
            <div class="mb-1 breadcrumb-right">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                        <i data-feather="save"></i>
                        <span class="active-sorting text-primary">{{ __('admin.actions.save') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div id="translations-wrapper" class="w-100">
                        @php
                        $translations = old('translations', $item->cancelTermTranslations ?? []);

                        $availableLangs = config('languages.available');
                        $usedLangs = collect($translations)->pluck('locale')->toArray();
                        @endphp

                        @foreach($translations as $tr)
                        <div class="translation-row input-group mb-1" data-locale="{{ $tr['locale'] ?? $tr->locale }}">
                            <span class="input-group-text">
                                {{ __('admin.'.($availableLangs[$tr['locale'] ?? $tr->locale] ?? strtoupper($tr['locale'] ?? $tr->locale))) }}

                            </span>
                            <input type="hidden" name="translations[{{ $loop->index }}][locale]" value="{{ $tr['locale'] ?? $tr->locale }}">
                            <textarea type="text" class="form-control form-control-solid editor" name="translations[{{ $loop->index }}][content]">{!! $tr['content'] ?? $tr->content !!}</textarea>
                            <button type="button" class="btn btn-danger remove-translation">-</button>
                        </div>
                        @endforeach
                    </div>
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
        </div>
    </div>
</form>
@stop

@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>
<script>
    CKEDITOR.editorConfig = function(config) {
        config.language = 'es';
        config.uiColor = '#F7B42C';
        config.height = 200;
        config.toolbarCanCollapse = true;
    };
    var editor = CKEDITOR.replaceAll('editor');

</script>
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
   
            <textarea type="text" class="form-control form-control-solid editor" name="translations[${index}][content]"></textarea>

            <button type="button" class="btn btn-danger remove-translation">-</button>
        `;
        wrapper.appendChild(div);

        // Initialize CKEditor only for the new textarea
        const newTextarea = div.querySelector('textarea.editor');
        if (newTextarea) {
            CKEDITOR.replace(newTextarea);
        }

        // Disable selected language in dropdown
        select.querySelector('option[value="' + lang + '"]').disabled = true;
        select.value = '';
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-translation')) {
            const row = e.target.closest('.translation-row');
            const lang = row.getAttribute('data-locale');
            // Enable language in dropdown again
            const select = document.getElementById('language-select');
            const option = select.querySelector(`option[value="${lang}"]`);
            if (option) option.disabled = false;
            row.remove();
        }
    });

</script>

@endpush


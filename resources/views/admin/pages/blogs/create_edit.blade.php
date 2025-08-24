@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('blogs.plural') }}</title>
@endsection
@push('styles')
    <style>

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
          action="{{ isset($item) ? route('admin.blogs.update', $item->id) : route('admin.blogs.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('blogs.actions.edit') : __('blogs.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block ">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('blogs.actions.save') }}</span>
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
                                        <label class="form-label">{{ __('gifts.title') }}</label>
                                        <input type="text" class="form-control" name="translations[{{ $index }}][title]" value="{{ $t['title'] ?? '' }}" placeholder="{{ __('gifts.title') }}">

                                    </div>


                                    <div class="col-md-12">
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
                    </div>

                    <div class="row">
                       
                        <div class="mb-1 col-md-3  @error('publisher_name') is-invalid @enderror">
                            <label class="form-label" for="publisher_name">{{ __('admin.publisher_name') }}</label>
                            <input type="text" name="publisher_name" id="publisher_name" class="form-control" placeholder=""
                                   value="{{ $item->publisher_name ?? old('publisher_name') }}" required/>
                            @error('publisher_name')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-2  @error('active') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active"
                                        value="1" id="active"
                                    @checked($item->active ?? false )/>
                                <label class="form-check-label" for="active">{{ __('blogs.active') }}</label>
                            </div>
                            @error('active')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                       
                        <div class="mb-1 col-md-6 @error('image') is-invalid @enderror">
                            <label class="form-label" for="image">{{ __('blogs.image') }}</label>
                            <input type="file" class="form-control input" name="image" id="image">
                            @error('image')
                            <span class="error">{{ $message }}</span>
                            @enderror
                            <div>
                                <br>
                                @if(isset($item) && !empty($item->cover))
                                    <img src="{{ $item->photo }}"
                                         class="img-fluid img-thumbnail">
                                @endif
                            </div>
                        </div>
                        <div class="mb-1 col-md-6 @error('publisher_image') is-invalid @enderror">
                            <label class="form-label" for="publisher_image">{{ __('blogs.publisher_image') }}</label>
                            <input type="file" class="form-control input" name="publisher_image" id="publisher_image">
                            @error('publisher_image')
                            <span class="error">{{ $message }}</span>
                            @enderror
                            <div>
                                <br>
                                @if(isset($item) && !empty($item->publisher_image))
                                    <img src="{{ $item->publisherphoto }}"
                                         class="img-fluid img-thumbnail">
                                @endif
                            </div>
                        </div>
                         <div class="card" style="border: 1px solid;padding: 20px;">
                        <h3 class="price">{{ __('articles.images') }}</h3>
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
                            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
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
            <label class="form-label">{{ __('gifts.title') }}</label>
            <input type="text" class="form-control" name="translations[${index}][title]" value="" placeholder="{{ __('gifts.title') }}">
        </div>

        <div class="col-md-12">
            <label class="form-label">{{ __('gifts.description') }}</label>
            <textarea class="form-control" name="translations[${index}][description]" placeholder="{{ __('gifts.description') }}"></textarea>
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


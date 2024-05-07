@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('articles.plural') }}</title>
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
          action="{{ isset($item) ? route('admin.articles.update', $item->id) : route('admin.articles.store') }}">
        <input type="hidden" name="_method" value="{{ isset($item) ? 'PUT' : 'POST' }}">
        @csrf
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('articles.actions.edit') : __('articles.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary me-1 waves-effect">
                            <i data-feather="save"></i>
                            <span class="active-sorting text-primary">{{ __('articles.actions.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-6  @error('title_en') is-invalid @enderror">
                            <label class="form-label" for="title_en">{{ __('admin.title_en') }}</label>
                            <input type="text" name="title_en" id="title_en" class="form-control" placeholder=""
                                   value="{{ $item->title_en ?? old('title_en') }}" required/>
                            @error('title_en')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6  @error('title_ar') is-invalid @enderror">
                            <label class="form-label" for="title_ar">{{ __('admin.title_ar') }}</label>
                            <input type="text" name="title_ar" id="title_ar" class="form-control" placeholder=""
                                   value="{{ $item->title_ar ?? old('title_ar') }}" required/>
                            @error('title_ar')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6  @error('start_date') is-invalid @enderror">
                            <label class="form-label" for="start_date">{{ __('articles.start_date') }}</label>
                            <input type="text" name="start_date" id="start_date" class="form-control flatpickr-basic" placeholder=""
                                   value="{{ $item->start_date ?? old('start_date') }}" required/>
                            @error('start_date')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6  @error('end_date') is-invalid @enderror">
                            <label class="form-label" for="end_date">{{ __('articles.end_date') }}</label>
                            <input type="text" name="end_date" id="end_date" class="form-control flatpickr-basic" placeholder=""
                                   value="{{ $item->end_date ?? old('end_date') }}" required/>
                            @error('end_date')
                            <span class="error">{{ $message }}</span>
                            @enderror
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
                        <div class="mb-1 col-md-6  @error('tags') is-invalid @enderror">
                            <label class="form-label" for="tags">{{ __('articles.tags') }}</label>
                            <textarea type="text" name="tags" id="tags" class="form-control" placeholder="{{ __('articles.tags_description') }}">{{ $item->tags??null }}</textarea>
                            @error('tags')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6  @error('type') is-invalid @enderror">
                            <label class="form-label" for="type">{{ __('articles.type') }}</label>
                            <select  name="type" id="type" class="form-control" placeholder="{{ __('articles.type') }}">
                                <option value="">{{ __('admin.select') }}</option>
                                <option value="article" {{ isset($item)? $item->type=='article'?'selected':'':''  }}>{{ __('articles.article') }}</option>
                                <option value="news" {{ isset($item)? $item->type=='news'?'selected':'':''  }}>{{ __('articles.news') }}</option>
                            </select>
                            @error('type')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-2  @error('active') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active"
                                        value="1" id="active"
                                    @checked($item->active ?? false )/>
                                <label class="form-check-label" for="active">{{ __('articles.active') }}</label>
                            </div>
                            @error('active')
                            <span class="error">{{ $message }}</span>
                            @enderror
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
@endpush

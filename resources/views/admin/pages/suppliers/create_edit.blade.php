@extends('admin.layouts.master')
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin') }}/vendors/css/forms/wizard/bs-stepper.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin') }}/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin') }}/css-rtl/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin') }}/css-rtl/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin') }}/css-rtl/plugins/forms/form-wizard.css">
@endpush
@section('title')
    <title>{{ config('app.name') }} | {{ __('suppliers.plural') }}</title>
@endsection
@section('content')

        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h1 class="bold mb-0 mt-1 text-dark">
                            <i data-feather="box" class="font-medium-2"></i>
                            <span>{{ isset($item) ? __('suppliers.actions.edit') : __('suppliers.actions.create') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-6 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">

                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">

                <section class="horizontal-wizard">
                    <div class="bs-stepper horizontal-wizard-example">
                        <div class="bs-stepper-header" role="tablist">
                            <div class="step" data-target="#account-details" role="tab" id="account-details-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-box">1</span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Account Details</span>
                                        <span class="bs-stepper-subtitle">Setup Account Details</span>
                                    </span>
                                </button>
                            </div>
                            <div class="line">
                                <i data-feather="chevron-right" class="font-medium-2"></i>
                            </div>
                            <div class="step" data-target="#personal-info" role="tab" id="personal-info-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-box">2</span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Personal Info</span>
                                        <span class="bs-stepper-subtitle">Add Personal Info</span>
                                    </span>
                                </button>
                            </div>
                            <div class="line">
                                <i data-feather="chevron-right" class="font-medium-2"></i>
                            </div>
                            <div class="step" data-target="#address-step" role="tab" id="address-step-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-box">3</span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Address</span>
                                        <span class="bs-stepper-subtitle">Add Address</span>
                                    </span>
                                </button>
                            </div>
                            <div class="line">
                                <i data-feather="chevron-right" class="font-medium-2"></i>
                            </div>
                            <div class="step" data-target="#social-links" role="tab" id="social-links-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-box">4</span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Social Links</span>
                                        <span class="bs-stepper-subtitle">Add Social Links</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <div id="account-details" class="content" role="tabpanel" aria-labelledby="account-details-trigger">
                                <div class="content-header">
                                    <h5 class="mb-0">Account Details</h5>
                                    <small class="text-muted">Enter Your Account Details.</small>
                                </div>
                                <form id="first_setup">
                                    <input type="hidden" id="first_setup" value="0">
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="name">{{ __('suppliers.name') }}</label>
                                            <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('suppliers.name') }}" required />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="email">{{ __('suppliers.email') }}</label>
                                            <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('suppliers.email') }}" aria-label="{{ __('suppliers.email') }}" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 form-password-toggle col-md-6">
                                            <label class="form-label" for="password">{{ __('suppliers.password') }}</label>
                                            <input type="password" name="password" id="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                        </div>
                                        <div class="mb-1 form-password-toggle col-md-6">
                                            <label class="form-label" for="confirm-password">{{ __('suppliers.password_confirmation') }}</label>
                                            <input type="password" name="confirm-password" id="confirm-password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-outline-secondary btn-prev" disabled>
                                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">{{ __('admin.previous') }}</span>
                                    </button>
                                    <button class="btn btn-primary btn-next" form_id="first_setup" type="button">
                                        <span class="align-middle d-sm-inline-block d-none">{{ __('admin.save_next') }}</span>
                                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="personal-info" class="content" role="tabpanel" aria-labelledby="personal-info-trigger">
                                <div class="content-header">
                                    <h5 class="mb-0">Personal Info</h5>
                                    <small>Enter Your Personal Info.</small>
                                </div>
                                <form>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="first-name">First Name</label>
                                            <input type="text" name="first-name" id="first-name" class="form-control" placeholder="John" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="last-name">Last Name</label>
                                            <input type="text" name="last-name" id="last-name" class="form-control" placeholder="Doe" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="country">Country</label>
                                            <select class="select2 w-100" name="country" id="country">
                                                <option label=" "></option>
                                                <option>UK</option>
                                                <option>USA</option>
                                                <option>Spain</option>
                                                <option>France</option>
                                                <option>Italy</option>
                                                <option>Australia</option>
                                            </select>
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="language">Language</label>
                                            <select class="select2 w-100" name="language" id="language" multiple>
                                                <option>English</option>
                                                <option>French</option>
                                                <option>Spanish</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-primary btn-prev">
                                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">{{ __('admin.previous') }}</span>
                                    </button>
                                    <button class="btn btn-primary btn-next">
                                        <span class="align-middle d-sm-inline-block d-none">{{ __('admin.save_next') }}</span>
                                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="address-step" class="content" role="tabpanel" aria-labelledby="address-step-trigger">
                                <div class="content-header">
                                    <h5 class="mb-0">Address</h5>
                                    <small>Enter Your Address.</small>
                                </div>
                                <form>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="address">Address</label>
                                            <input type="text" id="address" name="address" class="form-control" placeholder="98  Borough bridge Road, Birmingham" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="landmark">Landmark</label>
                                            <input type="text" name="landmark" id="landmark" class="form-control" placeholder="Borough bridge" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="pincode1">Pincode</label>
                                            <input type="text" id="pincode1" class="form-control" placeholder="658921" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="city1">City</label>
                                            <input type="text" id="city1" class="form-control" placeholder="Birmingham" />
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-primary btn-prev">
                                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">{{ __('admin.previous') }}</span>
                                    </button>
                                    <button class="btn btn-primary btn-next">
                                        <span class="align-middle d-sm-inline-block d-none">{{ __('admin.save_next') }}</span>
                                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="social-links" class="content" role="tabpanel" aria-labelledby="social-links-trigger">
                                <div class="content-header">
                                    <h5 class="mb-0">Social Links</h5>
                                    <small>Enter Your Social Links.</small>
                                </div>
                                <form>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="twitter">Twitter</label>
                                            <input type="text" id="twitter" name="twitter" class="form-control" placeholder="https://twitter.com/abc" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="facebook">Facebook</label>
                                            <input type="text" id="facebook" name="facebook" class="form-control" placeholder="https://facebook.com/abc" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="google">Google+</label>
                                            <input type="text" id="google" name="google" class="form-control" placeholder="https://plus.google.com/abc" />
                                        </div>
                                        <div class="mb-1 col-md-6">
                                            <label class="form-label" for="linkedin">Linkedin</label>
                                            <input type="text" id="linkedin" name="linkedin" class="form-control" placeholder="https://linkedin.com/abc" />
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-primary btn-prev">
                                        <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">{{ __('admin.previous') }}</span>
                                    </button>
                                    <button class="btn btn-success btn-submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            {{-- <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-1 col-md-4  @error('name') is-invalid @enderror">
                            <label class="form-label" for="name">{{ __('admin.name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder=""
                                   value="{{ $item->name ?? old('name') }}" required/>
                            @error('name')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-1 col-md-4  @error('code') is-invalid @enderror">
                            <label class="form-label" for="code">{{ __('suppliers.code') }}</label>
                            <input type="text" name="code" id="code" class="form-control" placeholder=""
                                   value="{{ $item->code ?? old('code') }}" required/>
                            @error('code')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('email') is-invalid @enderror">
                            <label class="form-label" for="email">{{ __('admin.email') }}</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder=""
                                   value="{{ $item->email ?? old('email') }}" required/>
                            @error('email')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('phone') is-invalid @enderror">
                            <label class="form-label" for="phone">{{ __('admin.phone') }}</label>
                            <input type="number" name="phone" id="phone" class="form-control" placeholder=""
                                   value="{{ $item->phone ?? old('phone') }}" required/>
                            @error('phone')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('address') is-invalid @enderror">
                            <label class="form-label" for="address">{{ __('admin.address') }}</label>
                            <input type="text" name="address" id="address" class="form-control" placeholder=""
                                   value="{{ $item->address ?? old('address') }}" />
                            @error('address')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('nationality') is-invalid @enderror">
                            <label class="form-label" for="nationality">{{ __('suppliers.nationality') }}</label>
                            <input type="text" name="nationality" id="nationality" class="form-control" placeholder=""
                                   value="{{ $item->nationality ?? old('nationality') }}" />
                            @error('nationality')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('birthdate') is-invalid @enderror">
                            <label class="form-label" for="birthdate">{{ __('suppliers.birthdate') }}</label>
                            <input type="text" name="birthdate" id="birthdate" class="form-control flatpickr-basic" placeholder=""
                                   value="{{ $item->birthdate ?? old('birthdate') }}" />
                            @error('birthdate')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('joining_date_from') is-invalid @enderror">
                            <label class="form-label" for="joining_date_from">{{ __('suppliers.joining_date_from') }}</label>
                            <input type="text" name="joining_date_from" id="joining_date_from" class="form-control flatpickr-basic" placeholder=""
                                   value="{{ $item->joining_date_from ?? old('joining_date_from') }}" />
                            @error('joining_date_from')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('joining_date_to') is-invalid @enderror">
                            <label class="form-label" for="joining_date_to">{{ __('suppliers.joining_date_to') }}</label>
                            <input type="text" name="joining_date_to" id="joining_date_to" class="form-control flatpickr-basic" placeholder=""
                                   value="{{ $item->joining_date_to ?? old('joining_date_to') }}" />
                            @error('joining_date_to')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('city_id') is-invalid @enderror">
                            <label class="form-label" for="city_id">{{ __('suppliers.city') }}</label>
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
                        <div class="mb-1 col-md-4  @error('password') is-invalid @enderror">
                            <label class="form-label">{{ __('users.password') }}</label>
                            <input class="form-control input" name="password"  placeholder="" type="password"
                                   autocomplete="false" readonly onfocus="this.removeAttribute('readonly');">
                            @error('password')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-4  @error('password_confirmation') is-invalid @enderror">
                            <label class="form-label">{{ __('users.password_confirmation') }}</label>
                            <input class="form-control input" name="password_confirmation"  placeholder="" type="password"
                                   autocomplete="false" readonly onfocus="this.removeAttribute('readonly');">
                            @error('password_confirmation')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-2  @error('active') is-invalid @enderror">
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="active"
                                        value="1" id="active"
                                        @checked($item->active ?? false )/>
                                <label class="form-check-label" for="active">{{ __('suppliers.active') }}</label>
                            </div>
                            @error('active')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-1 col-md-6 @error('image') is-invalid @enderror">
                            <label class="form-label" for="image">{{ __('suppliers.file') }}</label>
                            <input type="file" class="form-control input" name="image" id="image">
                            @error('image')
                            <span class="error">{{ $message }}</span>
                            @enderror
                            <div>
                                <br>
                                @if(isset($item) && !empty($item->image))
                                    <img src="{{ $item->photo }}"
                                         class="img-fluid img-thumbnail">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
@stop
@push('scripts')
    <script src="{{ asset('assets/admin') }}/vendors/js/forms/wizard/bs-stepper.min.js"></script>
        <script src="{{ asset('assets/admin') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>

        <script>

            function submitForm(form_id){
                @can('suppliers.create')

                $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
               });
               var data={};
               if(form_id=="first_setup"){
                data={
                        name:$("#first_setup #name").val(),
                        email:$("#first_setup #email").val(),
                        password:$("#first_setup #password").val(),
                        password_confirmation:$("#first_setup #confirm-password").val(),
                    };
                }

                $.ajax({
                    type:'POST',
                    url:"{{ route('admin.suppliers.store') }}",
                    data:data,
                    success:function(data){
                        runToast("نجح","تم الحفظ بنجاح");
                    }
                });
                @else
                alert("{{ __('admin.messages.donthavepermissions') }}")
            @endcan


        return true;
            }
        function runToast(title,message,type="success"){
                  let timer1, timer2;
                toast = document.querySelector(".toasts");
                (closeIcon = document.querySelector(".close")),
                (progress = document.querySelector(".progress"));
                $("#toast_header").text(title);
                $("#toast_message").text(message);

                toast.classList.add("active");
                progress.classList.add("active");
                timer1 = setTimeout(() => {
                toast.classList.remove("active");
                }, 5000);
                timer2 = setTimeout(() => {
                progress.classList.remove("active");
                }, 5300);
                closeIcon.addEventListener("click", () => {
                toast.classList.remove("active");
                setTimeout(() => {
                    progress.classList.remove("active");
                }, 300);
                clearTimeout(timer1);
                clearTimeout(timer2);
                });
            }
        </script>
    <script src="{{ asset('assets/admin') }}/form-wizard.js"></script>
@endpush

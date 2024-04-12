<?php $assetsPath = asset('assets/admin') ?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    @yield('title')
    <link rel="apple-touch-icon" href="{{ $assetsPath }}/images/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $assetsPath }}/images/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/vendors/css/extensions/toastr.min.css">

    @if (app()->getLocale()=='ar')
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/colors.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/components.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/pages/dashboard-ecommerce.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/plugins/charts/chart-apex.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/plugins/extensions/ext-component-toastr.css">
    @else
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css/colors.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css/components.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css/pages/dashboard-ecommerce.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css/plugins/charts/chart-apex.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css/plugins/extensions/ext-component-toastr.css">
@endif
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/plugins/forms/pickers/form-flat-pickr.css">

    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css/custom.css">
    @stack('styles')
</head>

<body class="pace-done vertical-layout vertical-menu-modern navbar-floating footer-static menu-expanded" data-open="click" data-menu="vertical-menu-modern" data-col="">
<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
            </ul>

        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">

            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon" data-feather="moon"></i></a></li>
            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none">
                        <span class="user-name fw-bolder">{{ auth()->user()->name }}</span>
                    </div>
                    <span class="avatar">
                        <img class="round" src="{{ $assetsPath }}/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="40" width="40">
                        <span class="avatar-status-online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="{{ route('admin.profile.index') }}">
                        <i class="me-50" data-feather="user"></i> {{ __('admin.profile') }}
                    </a>
                    <a class="dropdown-item" href="{{ route('language') }}">
                        <i class="me-50" data-feather="flag"></i> {{ __('admin.language') }}
                    </a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();" class="dropdown-item">
                        <i class="me-50" data-feather="power"></i> {{ __('admin.logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>

@include('admin.partials.sidebar')


<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
        </div>
        <div class="content-body">
            @include('flash::message')
            @include('admin.partials.errors')

            @yield('content')
        </div>
    </div>
</div>
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<footer class="footer footer-static footer-light">
    <p class="clearfix mb-0">
        <span class="float-md-start d-block d-md-inline-block mt-25">
            <span class="d-none d-sm-inline-block">{{ config('app.name') }}</span>
        </span>
        <span class="float-md-end d-none d-md-block"><i data-feather="heart"></i></span>
    </p>
</footer>
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>

<div class="modal fade text-start" id="modalDelete" tabindex="-1" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteForm" method="post" action="#">
            <input type="hidden" name="_method" value="DELETE">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">{{ __('admin.dialogs.delete.title') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __('admin.dialogs.delete.info') }}
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-danger">{{ __('admin.dialogs.delete.confirm') }}</button>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">{{ __('admin.dialogs.delete.cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

 <div class="modal fade text-start" id="modalRestore" tabindex="-1" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="restoreForm" method="post" action="">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel1">{{ __('users.dialogs.restore.title') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ __('users.dialogs.restore.info') }}
                    </div>
                    <div class="modal-footer">
                        <button type="submit"
                            class="btn btn-sm btn-danger">{{ __('users.dialogs.restore.confirm') }}</button>
                        <button type="button" class="btn btn-sm btn-primary"
                            data-bs-dismiss="modal">{{ __('users.dialogs.delete.cancel') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<script src="{{ $assetsPath }}/vendors/js/vendors.min.js"></script>
<script src="{{ $assetsPath }}/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
<script src="{{ $assetsPath }}/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
<script src="{{ $assetsPath }}/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ $assetsPath }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>

<script src="{{ $assetsPath }}/js/core/app-menu.js"></script>
<script src="{{ $assetsPath }}/js/core/app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>
<script src="https://cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>
    <script>
        CKEDITOR.editorConfig = function( config ) {
            config.language = 'es';
            config.uiColor = '#F7B42C';
            config.height = 200;
            config.toolbarCanCollapse = true;
        };
        var editor = CKEDITOR.replaceAll( 'editor' );
    </script>
<script>

    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
         var rangePickr = $('.flatpickr-range')
         if (rangePickr.length) {
            rangePickr.flatpickr({
            mode: 'range'
            });
        }
        var basicPickr = $('.flatpickr-basic')
        if (basicPickr.length) {
            basicPickr.flatpickr();
        }

        $('.select2-input').select2();
        $('.ajax_select2').select2({
            placeholder: "{{ __('admin.select') }}",
            ajax: {
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {results: data};
                },
                cache: true
            }
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '.delete_item', function (){
            var url= $(this).attr('data-url');
            $('#deleteForm').attr('action', url)
            $('#modalDelete').modal('show')
            return false;
        });
        $('body').on('click', '.restore_item', function (){
            var url= $(this).attr('data-url');
            $('#restoreForm').attr('action', url)
            $('#modalRestore').modal('show')
            return false;
        })

    $('.btn_clear').click(function() {
        $(this).parent().find('.ajax_select2,.select2').val(null).trigger("change")
    });

     })


</script>
@stack('scripts')
</body>

</html>

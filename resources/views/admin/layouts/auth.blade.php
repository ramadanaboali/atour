<?php $assetsPath = asset('assets/admin'); ?>
    <!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <title>{{ config('app.name') }}</title>
    <link rel="apple-touch-icon" href="{{ $assetsPath }}/images/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $assetsPath }}/images/favicon.png">
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/vendors/css/vendors-rtl.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/colors.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/components.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/core/menu/menu-types/horizontal-menu.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/pages/page-auth.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css/custom.css">
    <link rel="stylesheet" type="text/css" href="{{ $assetsPath }}/css-rtl/custom-rtl.css">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="horizontal-layout horizontal-menu blank-page navbar-floating footer-static  " data-open="hover" data-menu="horizontal-menu" data-col="blank-page">
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        @yield('content')
    </div>
</div>
<!-- END: Content-->


<!-- BEGIN: Vendor JS-->
<script src="{{ $assetsPath }}/vendors/js/vendors.min.js"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ $assetsPath }}/vendors/js/ui/jquery.sticky.js"></script>
<script src="{{ $assetsPath }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ $assetsPath }}/js/core/app-menu.js"></script>
<script src="{{ $assetsPath }}/js/core/app.js"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="{{ $assetsPath }}/js/scripts/pages/page-auth-login.js"></script>
<!-- END: Page JS-->

<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>
</body>
<!-- END: Body-->

</html>

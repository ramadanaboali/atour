<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/front') }}/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="{{ asset('assets/front') }}/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/front') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link href="{{ asset('assets/front') }}/css/style.css" rel="stylesheet">
    <link href="{{ asset('assets/front') }}/css/rtl.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/admin') }}/plugins/apexcharts/apexcharts.css">
    <link rel="icon" type="image/png" href="{{ asset('assets/front') }}/images/favicon.png" />
    <title>{{ config('app.name') }}</title>
</head>
<body class="bg-primary1">
<div class="container">
    <div class="text-center mt-5">
        <img class="img-fluid" src="{{ asset('assets/front') }}/images/logo.png">
    </div>
    <div class="row mt-5">
        <div class="col-md-3"></div>
        <div class="col-md-6">
             <h1 class="text-center fw-bold">عفوا يجب أن تقوم بتحميل التطبيق أو قم باستخدام جهاز الكمبيوتر أو اللابتوب</h1>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
<script src="{{ asset('assets/admin') }}/plugins/jquery/jquery.min.js"></script>
<script src="{{ asset('assets/front') }}/js/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="{{ asset('assets/front') }}/js//bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script src="{{ asset('assets/admin') }}/plugins/apexcharts/apexcharts.js"></script>
@stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('meta-data.global-meta-data')

        <link rel="image_src" href="https://chanchandev.s3.ap-northeast-2.amazonaws.com/imgs/chan-chan-dev-logo.png">
        <link rel="shortcut icon" href="https://chanchandev.s3.ap-northeast-2.amazonaws.com/imgs/favicon.ico">
        <link rel="alternate" href="https://weight.chanchandev.com/">
        <link rel="canonical" href="https://weight.chanchandev.com/">
        @include('meta-data.ga')
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body>
        @inertia
    </body>
</html>

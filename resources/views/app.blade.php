<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('meta-data.global-meta-data')

        <link rel="image_src" href="images/thumbnail.jpg">
        <link rel="shortcut icon" href="images/favicon.ico">
        <link rel="alternate" href="https://www.weight.chanchancreative.com/" hreflang="zh-Hant">
        <link rel="canonical" href="https://www.weight.chanchancreative.com/">
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link href="https://file.myfontastic.com/J27BndVsuo3sLQMjh5n9WX/icons.css" rel="stylesheet">

        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body>
        @inertia
    </body>
</html>

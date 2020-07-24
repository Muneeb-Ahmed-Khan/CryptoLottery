<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>{{config('app.name')}} - Cross Linguistic Learning Platform</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="robots" content="index,follow"/>
    <meta http-equiv="content-language" content="en"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Free Videos Uploading and Sharing Service"/>
    <meta name="keywords" content="videos upload, videos sharing, mp4 upload, video cloud storage, free upload videos, PPU sites"/>
    <meta property="og:url" content="/index"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="{{config('app.name','CLAP - Cross Linguistic Learning Platform')}} - Cross Linguistic Learning Platform"/>
    <meta property="og:description" content="{{config('app.name','CLAP - Cross Linguistic Learning Platform')}} - Cross Linguistic Learning Platform"/>
    <meta property="og:image" content="{{asset('images/capture.jpg')}}"/>
    <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" type="image/x-icon"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/main-homef195.css?v=2.1')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/media-homeffaf.css?v=1.4')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/icons-home8510.css?v=0.2')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/customeef3.css?v=0.3')}}"/>

    <link href="{{asset('css/toastr.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/dashboard.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}"/>
    <script type="text/javascript" src="{{asset('js/jquery-1.9.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/mainc619.js?v=1.0')}}"></script>


</head>
<body class="">
 
            @yield('content')

    <!--modal-->
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>

</body>
</html>

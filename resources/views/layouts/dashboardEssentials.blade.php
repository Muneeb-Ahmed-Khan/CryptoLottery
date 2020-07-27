<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>Dashboard - {{config('app.name')}} - Win Lotteries by BTC</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="robots" content="index,follow"/>
    <meta http-equiv="content-language" content="en"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Win Lotteries by BTC"/>
    <meta name="keywords" content=" Win Lotteries by BTC"/>
    <meta property="og:url" content="/index"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="{{config('app.name','CryptoLottery - Win Lotteries by BTC')}} - Win Lotteries by BTC"/>
    <meta property="og:description" content="{{config('app.name','CryptoLottery - Win Lotteries by BTC')}} - Win Lotteries by BTC"/>
    <meta property="og:image" content="{{asset('images/capture.jpg')}}"/>
    <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" type="image/x-icon"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}"/>
    
    
    <script type="text/javascript" src="{{asset('js/jquery-1.9.1.min.js')}}"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/main.js"></script>
    
    <link href="{{asset('css/toastr.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="{{asset('css/styles.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/icons.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/media.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/custom.css')}}">

    

    


</head>

<body class="body-hidden">
<div id="header">
        <a href="/" id="m-logo"></a>

        <div class="header-welcome m-none active"><a href="/">Hi, {{ Auth::user()->name }} !</a></div>
        <div class="header-display">


            <div class="hd-logout">

                <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="{{ url('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="icon-logout"></i>
                </a>

            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>

<main class="py-4">
            @yield('content')
</main>



</body>
</html>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{config('app.name')}}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="robots" content="index,follow"/>
    <meta http-equiv="content-language" content="en"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
    * {box-sizing: border-box;}

    body { 
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    }

    .header {
    overflow: hidden;
    background-color: white;
    }

    .header a {
    float: left;
    color: black;
    text-align: center;
    padding: 8px;
    text-decoration: none;
    font-size: 18px; 
    line-height: 25px;
    border-radius: 4px;
    }

    .header a.logo {
    font-size: 25px;
    font-weight: bold;
    }

    .header a:hover {
    background-color: #ddd;
    color: black;
    }

    .header a.active {
    background-color: dodgerblue;
    color: white;
    }

    .header-right {
    float: right;
    }

    @media  screen and (max-width: 500px) {
    .header a {
        float: none;
        display: block;
        text-align: left;
    }
    
    .header-right {
        float: none;
    }
    }


    .headerContent
    {
        max-width: 960px;
        margin: auto;
        margin-top : 1rem;
        margin-bottom : 1rem;

    }
    .searchBin
    {
        display: inline;
    }

    #navbarSupportedContent > ul > li > a {
        color: white;
    }

    #navbarSupportedContent > ul > li :hover {
        background-color: #084ab8;
    }


    /* width */
    ::-webkit-scrollbar {
    width: 4px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
    background: #f1f1f1; 
    }
    
    /* Handle */
    ::-webkit-scrollbar-thumb {
    background: #888; 
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
    background: #555; 
    }


    .fa {
    padding: 20px;
    font-size: 30px;
    width: 50px;
    text-align: center;
    text-decoration: none;
    margin: 5px 2px;
    }

    .fa:hover {
        opacity: 0.7;
    }

    .fa-facebook {
    background: #3B5998;
    color: white;
    }

    .fa-twitter {
    background: #55ACEE;
    color: white;
    }

    .fa-google {
    background: #dd4b39;
    color: white;
    }

    .fa-youtube {
    background: #bb0000;
    color: white;
    }

    .fa-instagram {
    background: #ea4c89;
    color: white;
    }


    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script type="text/javascript" src="{{asset('js/jquery-1.9.1.min.js')}}"></script>
    <link href="{{asset('css/toastr.css')}}" rel="stylesheet" type="text/css" />
</head>
    <body class="">
        <div>
            <div class="header">
                <div class="headerContent">
                    
                    <img src="{{asset('images/logo.jpeg')}}" width="120px" style="float:left" alt="Homepage"/>
                    
                    
                        <a href="#" class="fa fa-facebook nav-link" style="margin-left:100px;color:white"></a>
                        <a href="#" class="fa fa-twitter nav-link" style="color:white"></a>
                        <a href="#" class="fa fa-google nav-link" style="color:white"></a>
                        <a href="#" class="fa fa-instagram nav-link" style="color:white"></a>
                        <a href="#" class="fa fa-youtube nav-link" style="color:white"></a>
                    

                    <div class="header-right">
                        <a  class="nav-link" style="margin-left:10px; background-color: #64bf09!important; color:white" href="/register">Register</a>
                        <a  class="nav-link" style="margin-left:10px; background-color: #084ab8!important; color:white" href="/login">Login</a>
                    </div>
                </div>
            </div>
        </div>


    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color:#4f9707!important; margin-top: 10px; padding:0">
        <div class="navbar-collapse" id="navbarSupportedContent" style="max-width:1000px;margin:auto;">
            <ul class="nav">
            
            <li class="nav-item active">
                <a class="nav-link" href="/">HOME <span class="sr-only">(current)</span></a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="/freebitcoins">Free Bitcoins </a>
            </li>
            

            <li class="nav-item active">
                <a class="nav-link" href="/faq">Frequently Asked Questions </a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="/terms">Terms and Conditions</a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="/contact">Support Center</a>
            </li>

            </ul>
        </div>
    </nav>

        @yield('content')
        
        <!--modal-->
        <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>

    </body>
</html>

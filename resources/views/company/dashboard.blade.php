@extends('layouts.dashboardEssentials')
@section('content')
<div id="main" class="main-padding main-dashboard extend">
<div class="container">
        <button type="button" style='margin-top: 5px;' data-target="#pop-addLottery" data-toggle="modal" class="btn btn-primary">Add Lottery</button>
        <button type="button" style='margin-top: 5px;' data-target="#pop-deleteLottery" data-toggle="modal" class="btn btn-danger">Delete Lottery</button>
</div>
<br>
    <div class="main-content">
        <div class="mc-stats-detail">
            <div class="row">

            @foreach($admins as $admin)
                <div class="col-lg-4 mcs-balance">
                    <div class="mbox">
                        <div class="mbox-title">
                            <div class="s-title">
                                <h5>{{ $admin->id }}</h5>
                            </div>
                        </div>
                        <div class="mbox-content mbox-number">
                            <span class="highlight">{{ $admin->name }}</span>
                        </div>
                        <div class="mbox-link">
                            <a href="/company/{{ $admin->id }}" title="Admin">Admin <span class="pull-right"><i class="fa fa-arrow-right"></i></span></a>
                        </div>
                    </div>
                </div>
            @endforeach

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>


<!--  Register Popup      -->
<div class="modal fade modal-cuz" id="pop-addLottery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <form class="sp-form" id="login-form-1" method="post" action="{{ route('ManageLottery') }}">
                        @csrf
                        <div class="form-group">
                        <input name="name" type="text"placeholder="Product Name..." id="name-input"  class="form-control" autocomplete="off" required  >
                        </div>

                        <div class="form-group">
                            <input name="requiredUsers" type="number" min="1" placeholder="Maximum Paticipants..." id="name-input"  class="form-control" autocomplete="off" required  >
                        </div>


                        <div class="form-group">
                            <input name="ticketLimit" type="number" min="1" placeholder="Ticket Limit..." id="name-input"  class="form-control" autocomplete="off" required  >
                        </div>

                        <button id="login-submit" name="AddLottery" type="submit" class="btn btn-success btn-block mt20" style="background: #3b8de3 !important;">Register
                        </button>
                        <div id="login-loading" class="loading">
                            <div class="spinner">
                                <div class="bounce1"></div>
                                <div class="bounce2"></div>
                                <div class="bounce3"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!--    Delete Popup    -->
<div class="modal fade modal-cuz" id="pop-deleteLottery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <form class="sp-form" id="login-form-1" method="post" action="{{ route('ManageLottery') }}">
                        @csrf
                        @if ($errors->has('alreadytaken'))
                            <div class="alert alert-danger error-message" style="display:block;background:#51b74f" id="error-name">{{ $errors->first('alreadytaken') }}</div>
                        @else
                            <div class="alert alert-danger error-message" id="error-name"></div>
                        @endif
                        @if ($errors->has('success'))
                            <div class="alert alert-danger error-message" style="display:block;background:#51b74f" id="error-name">{{ $errors->first('success') }}</div>
                        @else
                            <div class="alert alert-danger error-message" id="error-name"></div>
                        @endif

                        <div class="form-group">
                            <input autocomplete="off" required name="schoolid" type="text" class="form-control" id="name-input" value="{{ old('schoolid') }}" placeholder="School ID...">
                        </div>

                        <button id="login-submit" type="submit" name="DeleteSchool" class="btn btn-success btn-block mt20" style="background: #3b8de3 !important;">Delete School
                        </button>
                        <div id="login-loading" class="loading">
                            <div class="spinner">
                                <div class="bounce1"></div>
                                <div class="bounce2"></div>
                                <div class="bounce3"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@if(!empty($errors))
<script type="text/javascript" src="{{asset('/js/toastr.js')}}"></script>
@foreach ($errors->all() as $error)
    <script>
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-center",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
    }
    Command: toastr["error"]("{{$error}}");
    </script>
@endforeach
@endif


@if(session()->has('success'))
<script type="text/javascript" src="{{asset('/js/toastr.js')}}"></script>
<script>
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-center",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
    }
    Command: toastr["success"]("{{__(session('success'))}}");
</script>
@endif

@if(session()->has('info'))
<script type="text/javascript" src="{{asset('/js/toastr.js')}}"></script>
<script>
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-center",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
    }
    Command: toastr["info"]("{{__(session('info'))}}");
</script>
@endif

@endsection

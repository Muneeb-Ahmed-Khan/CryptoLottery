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

            @foreach(array(["Muneeb"], ['Ahmed']) as $admin)
                <div class="col-lg-4 mcs-balance">
                    <div class="mbox">
                        <div class="mbox-title">
                            <div class="s-title">
                                <h5>1</h5>
                            </div>
                        </div>
                        <div class="mbox-content mbox-number">
                            <span class="highlight">MUNEEB</span>
                        </div>
                        <div class="mbox-link">
                            <a href="/company" title="Admin">Admin <span class="pull-right"><i class="fa fa-arrow-right"></i></span></a>
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
                        
                        <!-- Name of Product -->
                        <div class="form-group">
                            <input name="name" type="text"placeholder="Product Name..." class="form-control" autocomplete="off" required  >
                        </div>

                        <!-- Maximum Paticipants of Product -->
                        <div class="form-group">
                            <input name="requiredUsers" type="number" min="1" placeholder="Maximum Paticipants..." class="form-control" autocomplete="off" required  >
                        </div>

                        <!-- No. of Winners when lottery is done of Product -->
                        <div class="form-group">
                            <input name="no_of_winners" type="number" min="1" placeholder="Maximum Paticipants..." class="form-control" autocomplete="off" required  >
                        </div>

                        <!-- Tickets per Participant of Product -->
                        <div class="form-group">
                            <input name="ticketLimit" type="number" min="1" placeholder="Ticket Limit..." class="form-control" autocomplete="off" required  >
                        </div>

                        <!--Picture of Product -->
                        <div class="form-group">
                            <input name="picture" type="file" accept="image/*" class="form-control" autocomplete="off" required  >
                        </div>

                        <button id="login-submit" name="AddLottery" type="submit" class="btn btn-success btn-block mt20" style="background: #3b8de3 !important;">Add Product</button>
                        
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

                        <div class="form-group">
                            <input type="text" name="lotteryId" required autocomplete="off" class="form-control" placeholder="Lottery ID...">
                        </div>

                        <button id="login-submit" type="submit" name="DeleteLottery" class="btn btn-success btn-block mt20" style="background: #3b8de3 !important;">Delete Lottery </button>
                        
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

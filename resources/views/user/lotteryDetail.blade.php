@extends('layouts.dashboardEssentials')
@section('content')

<style>
.col-lg-custom {
    min-width : 300px;
    width: 100%;
}
.card {
    box-shadow: 0 0.46875rem 2.1875rem rgba(4,9,20,0.03), 0 0.9375rem 1.40625rem rgba(4,9,20,0.03), 0 0.25rem 0.53125rem rgba(4,9,20,0.05), 0 0.125rem 0.1875rem rgba(4,9,20,0.03);
    border-width: 0;
    transition: all .2s;
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(26,54,126,0.125);
    border-radius: .25rem;
}

.card-body {
    flex: 1 1 auto;
    padding: 1.25rem;
}



.card-title {
    text-transform: uppercase;
    color: rgba(13,27,62,0.7);
    font-weight: bold;
    font-size: 20px;
}
.mb-0, .my-0 {
    margin-bottom: 0 !important;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: rgba(0,0,0,0);
}

.container
{
    padding :0px;
    margin : 0px;
}
</style>


<div id="main" class="main-padding main-dashboard extend">



    <div class="col-lg-custom">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h6 class="card-title">Lottery Picture</h6>
            
                <div class="form-group">
                    
                    <div class="form-group" style=" float: left; width: 47%; height: 100%; text-align:center">
                        <img src="{{ '/'.$lottery[0]->product_picture }}" style="height: 400px; max-width:100% ; margin-left: 20px;" alt="Lottery Image"/>
                    </div>

                    <div class="form-group" style=" float: right; width: 47%; height: 100%;">
                        <h6 class="card-title">{{ $lottery[0]->name }}</h6>
                        </br>


                        <table class="mb-0 table">
                            <thead>
                                
                            </thead>
                            <tbody>

                                <tr>
                                    <td>Cost (BTC) </td> <td> <b>{{ $lottery[0]->cost_of_lottery }}</b> </td>
                                </tr>
                            
                                <tr>
                                    <td>Maximum Allowed Participants</td> <td> {{ $lottery[0]->max_participants }}</td>
                                </tr>

                                <tr>
                                    <td>Maximum Allowed Winners</td> <td> {{ $lottery[0]->no_of_winners }} </td>
                                </tr>

                                <tr>
                                    <td>Current Progress (%) </td> <td> <progress id="file" value="{{ $transactions->count() }}" max="{{ $lottery[0]->max_participants }}"></progress> {{ ($transactions->count() / $lottery[0]->max_participants) * 100 }}% &nbsp;&nbsp;&nbsp; ({{ $transactions->count() }}/{{ $lottery[0]->max_participants }})</td>
                                </tr>
                                <tr>
                                    <td>Allowed Ticket per Participant</td> <td> {{ $lottery[0]->max_tickets }} </td>
                                </tr>

                            </tbody>
                        </table>
                        </br>
                        <a type="button" style="width:100%;" href="/user/{{ $lottery[0]->id }}/buyTicket"  class="btn btn-primary">Buy</a>

                    </div>
                </div>
            </div>

            </br>
            </br>
            </br>
            </br>


            <div class="card-body">
                <h6 class="card-title">Transactions</h6>
                <table class="mb-0 table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th> </th>
                            <th>Transactions ID</th>
                            <th>Payment Time</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $i = 0; ?>
                        @foreach($transactions as $transaction)
                        <tr>
                            <th scope="row"><?php $i = $i+1; echo $i; ?></th>
                            <td>{{ $transaction->username }}</td>
                            <td> </td>
                            <td>{{  $transaction->transaction_token  }}</td>
                            <td>{{  $transaction->created_at  }}</td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>


</div>

<script type="text/javascript" src="{{asset('/js/toastr.js')}}"></script>

@if(!empty($errors))
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

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

<br>
    

    <div class="col-lg-custom">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h6 class="card-title">{{ $lotteries[0]->name }} ({{ $lotteries[0]->cost_of_lottery }} BTC)</h6>
                <table class="mb-0 table">
                    <thead>
                        <tr>
                            
                            <th>Winner</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; ?>
                        @foreach($winners as $winer)
                        <tr>
                            <td>{{ $winer->email }}</td>
                            <td>{{ $winer->address }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>

</div>

@endsection

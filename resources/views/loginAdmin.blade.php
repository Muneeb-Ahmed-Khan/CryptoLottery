@extends('layouts.claplayout')
@section('content')
<div class="page">
    <div class="page-single">
        <div class="container">
            <div class="row">
                <div class="col col-login mx-auto">
                    <div class="text-center mb-6">
                        <img src="{{asset('images/transparency.png')}}"  style=" height: 6rem !important;" alt="LOGO">
                    </div>
                    <form class="card"  method="post" action="{{ url('login') }}">
                    @csrf

                    

                        <div class="card-body p-6">

                            @if ($errors->has('invalid'))
                                <div style="display: block;" id="error-message" class="alert alert-danger">{{ $errors->first('invalid') }}</div>
                            @else
                                <div style="display: none;" id="error-message" class="alert alert-danger"></div>
                            @endif

                            @if ($errors->has('email'))
                                @foreach ($errors->get('email') as $message)
                                    @if($message == 'Email Not Registered')
                                        <div class="alert alert-danger error-message" style="display:block" id="error-name">{{ $message }}</div>
                                    @else
                                        <div class="alert alert-danger error-message" style="display:block" id="error-name">{{ $message }}&nbsp;<a href="email/resend/{{ session()->get('role') }}/{{ old('email') }}" class="pull-right md-forgot" title="Resend Email">Resend Email</a></div>
                                    @endif
                                @endforeach
                            @endif

                            <div class="card-title text-center">Login to your Account</div>
                            
                            <div class="form-group">
                                <input type="email" name="email"  required style="font-size: unset!important;" class="form-control form-control-lg"  placeholder="email">
                                
                            </div>
                            

                            <div class="form-group">
                                <input type="password" name="password" style="font-size: unset!important;" class="form-control form-control-lg" required  placeholder="Password">
                            </div>

                            <div class="form-group" hidden>
                                <select required class="form-control" name="role">
                                        <option value="admin">Admin</option>
                                </select>
                            </div>
                            

                            <div class="form-footer">
                                <button type="submit" class="btn btn-danger btn-block" name="loginForm">Login</button>
                            </div>

                            <div class="text-center text-muted mt-3">
                                Don't have account yet? <a href="/register">Register</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
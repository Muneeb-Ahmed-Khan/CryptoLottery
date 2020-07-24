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
                        <form class="card"  method="POST" action="{{ url('register') }}">
                        @csrf
                            <div class="card-body p-6">
                                <div class="card-title text-center">Register</div>

                                @if ($errors->has('success'))
                                    <div class="alert alert-danger error-message" style="display:block;background:#51b74f; color: white; border-color: #f5f5f5" id="error-name">{{ $errors->first('success') }}</div>
                                @else
                                    <div class="alert alert-danger error-message" id="error-name"></div>
                                @endif

                                <div class="form-group">
                                    <input type="text" name="name" style="font-size: unset!important;" class="form-control form-control-lg" required placeholder="Username">
                                    @if ($errors->has('name'))
                                    <div class="alert alert-danger error-message" style="display:block" id="error-name">{{ $errors->first('name') }}</div>
                                    @else
                                        <div class="alert alert-danger error-message" id="error-name"></div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <input type="email" name="email" style="font-size: unset!important;" class="form-control form-control-lg"  required placeholder="Email">
                                    @if ($errors->has('email'))
                                        <div class="alert alert-danger error-message" style="display:block" id="error-name">{{ $errors->first('email') }}</div>
                                    @else
                                        <div class="alert alert-danger error-message" id="error-name"></div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <input type="password" name="password" style="font-size: unset!important;" class="form-control form-control-lg" required placeholder="Password">
                                    @if ($errors->has('password'))
                                        <div class="alert alert-danger error-message" style="display:block" id="error-name">{{ $errors->first('password') }}</div>
                                    @else
                                        <div class="alert alert-danger error-message" id="error-name"></div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <input type="password" name="password_confirmation" style="font-size: unset!important;" class="form-control form-control-lg" required name="confirmPassword" placeholder="Confirm Password">
                                    @if ($errors->has('password'))
                                        <div class="alert alert-danger error-message" style="display:block" id="error-name">{{ $errors->first('password') }}</div>
                                    @else
                                        <div class="alert alert-danger error-message" id="error-name"></div>
                                    @endif
                                </div>

                                <div class="form-footer">
                                    <button type="submit" class="btn btn-danger btn-block" name="registerForm">Register</button>
                                </div>
                                <div class="text-center text-muted mt-3">
                                    Already have an account?  <a href="/">Login</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
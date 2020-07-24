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
                    <form class="card" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    

                        <div class="card-body p-6">

                            <div class="card-title text-center">Reset your Password</div>
                            
                            <div class="form-group">
                                <input type="email" name="email"  required  value="{{ old('email') }}"  style="font-size: unset!important;" class="form-control form-control-lg"  placeholder="email">
                                @if ($errors->has('email'))
                                    <div class="alert alert-danger error-message" style="display:block" id="error-name">{{ $errors->first('email') }}</div>
                                @else
                                    <div class="alert alert-danger error-message" id="error-name"></div>
                                @endif
                            </div>
                            

                            <div class="form-group" hidden>
                                <select required class="form-control" name="role">
                                        <option value="user">User</option>
                                </select>
                            </div>

                            <div class="form-footer">
                                <button type="submit" class="btn btn-danger btn-block" name="loginForm">{{ __('Send Password Reset Link') }}</button>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

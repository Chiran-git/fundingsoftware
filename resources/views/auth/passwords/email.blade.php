@extends('layouts.auth')

@section('title')
{{ __('Forgot Password') }}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card pt-4 rounded form-container form_wrapper form_wrapper--shadow form_wrapper--alt">
                <div class="form__head">
                    <h4 class="mb-0"><img src="{{ asset('images/RocketJar_auth.png') }}" alt="logo" class="img-fluid"></h4>
                    <p class="text-uppercase">fund projects that <strong>matter</strong></p>
                </div>

                <div class="form__body form_fields">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label field_label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-5">
                            <div class="col">
                                <button type="submit" class="btn rounded-pill w-100">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col form__actions text-center">
                                <a class="text-link text-link--black" href="{{ route('login') }}">
                                    {{ __('Back to Login') }}
                                </a><br/>
                                <a class="text-link text-link--black" href="{{ route('org-signup') }}">
                                    {{ __('New user? Sign Up') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

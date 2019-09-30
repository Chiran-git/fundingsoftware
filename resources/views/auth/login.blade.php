@extends('layouts.auth')

@section('title')
{{ __('Login') }}
@endsection

@section('content')
            <div class="row justify-content-center">
                <div class="col-md-6">
                        @include('partials.common.flash-message')
                    <div class="card pt-4 rounded form-container form_wrapper form_wrapper--shadow form_wrapper--alt">
                        <div class="form__head">
                            <h4 class="mb-0"><img src="{{ asset('images/RocketJar_auth.png') }}" alt="logo" class="img-fluid"></h4>
                            <p class="text-uppercase">fund projects that <strong>matter</strong></p>
                        </div>

                        <div class="form__body form_fields">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label field_label text-md-right">{{ __('Email Address') }}</label>

                                    <div class="col">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label field_label text-md-right">{{ __('Password') }}</label>

                                    <div class="col">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col field_checkbox">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mt-4 mb-3">
                                    <div class="col">
                                        <button type="submit" class="btn rounded-pill w-100">
                                            {{ __('Log In') }}
                                        </button>
                                    </div>
                                </div>
                                @if (Route::has('password.request'))
                                    <div class="form-group row mb-5">
                                        <div class="col form__actions text-center">
                                            <a class="text-link text-link--black" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a><br/>
                                            <a class="text-link text-link--black" href="{{ route('org-signup') }}">
                                                {{ __('New user? Sign Up') }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
@endsection

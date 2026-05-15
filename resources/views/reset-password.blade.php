@extends('user::layouts.app')

@section('title', __('user::auth.forgot_password_title'))

@section('title')
    <h1 class="text-3xl font-extrabold text-center text-gray-900">{{ __('user::auth.forgot_password_title') }}</h1>
@endsection

@section('content')

    <x-theme::error-messages />

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <x-theme::email-field id="email" :label="__('user::auth.email')" :value="$email ?? old('email')" required autofocus />

        <x-theme::password-field id="password" :label="__('user::auth.password')" required />

        <x-theme::password-field id="password_confirmation" :label="__('user::auth.password_confirmation')" required />

        <div class="flex items-center justify-between">
            <x-theme::submit-button>
                {{ __('user::auth.reset_password_button') }}
            </x-theme::submit-button>
        </div>
    </form>
@endsection

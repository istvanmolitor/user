@extends('user::layouts.app')

@section('title', __('user::auth.forgot_password_title'))

@section('title')
    <h1 class="text-3xl font-extrabold text-center text-gray-900">{{ __('user::auth.forgot_password_title') }}</h1>
@endsection

@section('content')

    @if (session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <x-theme::error-messages />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <x-theme::email-field id="email" :label="__('user::auth.email')" :value="old('email')" required autofocus />

        <div class="flex items-center justify-between flex-col gap-4">
            <x-theme::submit-button>
                {{ __('user::auth.send_reset_link') }}
            </x-theme::submit-button>

            <x-theme::link-button href="{{ route('login') }}">
                {{ __('user::auth.login_title') }}
            </x-theme::link-button>
        </div>
    </form>
@endsection

@extends('user::layouts.app')

@section('title', __('user::auth.login_title') ?? 'Bejelentkezés')

@section('title')
    <h1 class="text-3xl font-extrabold text-center text-gray-900">{{ __('user::auth.login_title') ?? 'Bejelentkezés' }}</h1>
@endsection

@section('content')

    @if (session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <x-theme::error-messages />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <x-theme::email-field id="email" :label="__('user::auth.email') ?? 'E-mail cím'" :value="old('email')" required autofocus />

        <x-theme::password-field id="password" :label="__('user::auth.password') ?? 'Jelszó'" required />

        <div class="flex items-center justify-between flex-col gap-4">
            <x-theme::submit-button>
                {{ __('user::auth.login_button') }}
            </x-theme::submit-button>

            <div class="flex flex-col items-center gap-2">
                <x-theme::link-button href="{{ route('password.request') }}">
                    {{ __('user::auth.forgot_password') ?? 'Elfelejtetted a jelszavad?' }}
                </x-theme::link-button>

                <x-theme::link-button href="{{ route('register') }}">
                    {{ __('user::auth.no_account') ?? 'Nincs még fiókod?' }}
                </x-theme::link-button>
            </div>
        </div>
    </form>
@endsection

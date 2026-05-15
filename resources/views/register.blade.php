@extends('user::layouts.app')

@section('title', __('user::auth.register_title') ?? 'Regisztráció')

@section('header')
    <h1 class="text-3xl font-extrabold text-center text-gray-900">{{ __('user::auth.register_title') ?? 'Regisztráció' }}</h1>
@endsection

@section('content')

    <x-theme::error-messages />

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <x-theme::label for="name" :value="__('user::auth.name') ?? 'Név'" />
            <x-theme::input type="text" name="name" id="name" :value="old('name')" required autofocus />
        </div>

        <x-theme::email-field id="email" :label="__('user::auth.email') ?? 'E-mail cím'" :value="old('email')" required />

        <x-theme::password-field id="password" :label="__('user::auth.password') ?? 'Jelszó'" required />

        <x-theme::password-field id="password_confirmation" :label="__('user::auth.password_confirmation') ?? 'Jelszó újra'" required />

        <div class="flex items-center justify-between flex-col gap-4">
            <x-theme::submit-button>
                {{ __('user::auth.register_button') ?? 'Regisztráció' }}
            </x-theme::submit-button>

            <x-theme::link-button href="{{ route('login') }}">
                {{ __('user::auth.already_registered') ?? 'Már regisztráltál?' }}
            </x-theme::link-button>
        </div>
    </form>
@endsection

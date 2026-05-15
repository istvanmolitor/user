@extends('user::layouts.app')

@section('title', __('user::auth.register_title') ?? 'Regisztráció')

@section('header')
    <h1 class="text-3xl font-extrabold text-center text-gray-900">{{ __('user::auth.register_title') ?? 'Regisztráció' }}</h1>
@endsection

@section('content')

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('user::auth.name') ?? 'Név' }}</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('user::auth.email') ?? 'E-mail cím' }}</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('user::auth.password') ?? 'Jelszó' }}</label>
            <input type="password" name="password" id="password" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">{{ __('user::auth.password_confirmation') ?? 'Jelszó újra' }}</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="flex items-center justify-between flex-col gap-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                {{ __('user::auth.register_button') ?? 'Regisztráció' }}
            </button>

            <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('login') }}">
                {{ __('user::auth.already_registered') ?? 'Már regisztráltál?' }}
            </a>
        </div>
    </form>
@endsection

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

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('user::auth.email') }}</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                   placeholder="{{ __('user::auth.email_placeholder') }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="flex items-center justify-between flex-col gap-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                {{ __('user::auth.send_reset_link') }}
            </button>

            <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('login') }}">
                {{ __('user::auth.login_title') }}
            </a>
        </div>
    </form>
@endsection

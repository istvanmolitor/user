@extends(config('user.layouts.guest'))

@section('title', __('user::auth.login_title') ?? 'Bejelentkezés')

@section('content')
    <h1 class="text-2xl font-bold mb-6 text-center">{{ __('user::auth.login_title') ?? 'Bejelentkezés' }}</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('user::auth.email') ?? 'E-mail cím' }}</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-6">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('user::auth.password') ?? 'Jelszó' }}</label>
            <input type="password" name="password" id="password" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                {{ __('user::auth.login_button') ?? 'Bejelentkezés' }}
            </button>
        </div>
    </form>
@endsection

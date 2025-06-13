@extends('admin::layouts.base')

@section('title')
    Bejelentkezés
@endsection

@section('body')
    <div class="mt-6">
        <div class="row">
            <div class="col-12 offset-0 col-sm-12 offset-sm-0 col-md-6 offset-md-3 col-lg-4 offset-lg-4 pt-5">
                <div class="login-logo">
                    <a href="{{ route('home') }}">{!! config('admin.logo') !!}</a>
                </div>
                <login-form title="Bejelentkezés"></login-form>
            </div>
        </div>
    </div>
@endsection
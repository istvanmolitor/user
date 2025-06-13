@extends('user::admin.layouts.user')

@section('title')
    Felhasználók
@endsection

@section('breadcrumb')
    {{ Breadcrumbs::render('user.index') }}
@endsection

@section('actions')
    <x-menu name="userActions" template="admin::menu.actions"></x-menu>
@endsection

@section('content')
    {!! $users->render() !!}
@endsection

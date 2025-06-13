@extends('user::admin.layouts.user')

@section('title')
    Felhasználó létrehozása
@endsection

@section('breadcrumb')
    {{ Breadcrumbs::render('user.create') }}
@endsection

@section('actions')
    <x-menu name="userActions" template="admin::menu.actions"></x-menu>
@endsection

@section('content')
    <user-edit-form></user-edit-form>
@endsection

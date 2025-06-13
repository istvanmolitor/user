@extends('user::admin.layouts.user')

@section('title')
    Felhasználó szerkesztése
@endsection

@section('breadcrumb')
    {{ Breadcrumbs::render('user.edit', $user) }}
@endsection

@section('actions')
    <x-menu name="userActions" template="admin::menu.actions" :params="[$user]"></x-menu>
@endsection

@section('content')
    <user-edit-form :id="{{ $user->id }}"></user-edit-form>
@endsection
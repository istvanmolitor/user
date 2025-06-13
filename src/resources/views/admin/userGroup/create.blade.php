@extends('user::admin.layouts.userGroup')

@section('title')
    Felhasználói csoport létrehozása
@endsection

@section('breadcrumb')
    {{ Breadcrumbs::render('userGroup.create') }}
@endsection

@section('actions')
    <x-menu name="userGroupActions" template="admin::menu.actions"></x-menu>
@endsection

@section('content')
    <user-group-edit-from></user-group-edit-from>
@endsection

@extends('user::admin.layouts.permission')

@section('title')
    Jogosultság létrehozása
@endsection

@section('breadcrumb')
    {{ Breadcrumbs::render('permission.create') }}
@endsection

@section('actions')
    <x-menu name="permissionActions" template="admin::menu.actions"></x-menu>
@endsection

@section('content')
    <permission-edit-form></permission-edit-form>
@endsection

@extends('user::admin.layouts.permission')

@section('title')
    Jogosultság módosítása
@endsection

@section('breadcrumb')
    {{ Breadcrumbs::render('permission.edit', $permission) }}
@endsection

@section('actions')
    <x-menu name="permissionActions" template="admin::menu.actions" :params="[$permission]"></x-menu>
@endsection

@section('content')
    <permission-edit-form :id="{{$permission->id}}"></permission-edit-form>
@endsection

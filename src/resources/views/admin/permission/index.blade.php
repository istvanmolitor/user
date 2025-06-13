@extends('user::admin.layouts.permission')

@section('title')
    Jogosultságok
@endsection

@section('breadcrumb')
    {{ Breadcrumbs::render('permission.index') }}
@endsection

@section('actions')
    <x-menu name="permissionActions" template="admin::menu.actions"></x-menu>
@endsection

@section('content')
    {!! $permissions->render() !!}
@endsection


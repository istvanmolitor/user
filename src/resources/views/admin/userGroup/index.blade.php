@extends('user::admin.layouts.userGroup')

@section('title')
    Felhasználói csoportok
@endsection

@section('breadcrumb')
    {{ Breadcrumbs::render('userGroup.index') }}
@endsection

@section('actions')
    <x-menu name="userGroupActions" template="admin::menu.actions"></x-menu>
@endsection

@section('content')
    {!! $userGroups->render() !!}
@endsection


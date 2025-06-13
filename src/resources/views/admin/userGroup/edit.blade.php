@extends('user::admin.layouts.userGroup')

@section('title')
    Felhasználói csoport módosítása
@endsection

@section('breadcrumb')
    {{ Breadcrumbs::render('userGroup.edit', $userGroup) }}
@endsection

@section('actions')
    <x-menu name="userGroupActions" template="admin::menu.actions" :params="[$userGroup]"></x-menu>
@endsection

@section('content')
    <user-group-edit-from :id="{{ $userGroup->id }}"></user-group-edit-from>
@endsection

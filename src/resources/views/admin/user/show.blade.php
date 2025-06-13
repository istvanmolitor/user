@extends('user::admin.layouts.user')

@section('title')
    Felhasználó adatai
@endsection

@section('breadcrumb')
    {{ Breadcrumbs::render('user.show', $user) }}
@endsection

@section('actions')
    <x-menu name="userActions" template="admin::menu.actions" :params="[$user]"></x-menu>
@endsection

@section('content')
    <dl>
        <dt>E-mail</dt>
        <dd>{{ $user->email }}</dd>
        <dt>Név</dt>
        <dd>{{ $user->name }}</dd>
    </dl>
    <h2>Felhasználói csoportok</h2>
    @foreach($user->userGroups as $userGroup)
        <h3>{{ $userGroup->name }}</h3>
        <ul>
            @foreach($userGroup->permissions as $permission)
                <li>{{ $permission->name }} - {{ $permission->description }}</li>
            @endforeach
        </ul>
    @endforeach

@endsection

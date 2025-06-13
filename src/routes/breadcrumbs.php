<?php

Breadcrumbs::for('user.index', function ($trail) {
    $trail->parent('admin');
    $trail->push('Felhasználók', route('user.index'));
});

Breadcrumbs::for('user.create', function ($trail) {
    $trail->parent('user.index');
    $trail->push('Létrehozás', route('user.create'));
});

Breadcrumbs::for('user.edit', function ($trail, $user) {
    $trail->parent('user.index');
    $trail->push('Szerkesztés', route('user.edit', $user));
});

Breadcrumbs::for('user.show', function ($trail, $user) {
    $trail->parent('user.index');
    $trail->push('Profil', route('user.show', $user));
});

/***************************************************************************/

Breadcrumbs::for('userGroup.index', function ($trail) {
    $trail->parent('admin');
    $trail->push('Felhasználói csoportok', route('user.group.index'));
});

Breadcrumbs::for('userGroup.create', function ($trail) {
    $trail->parent('userGroup.index');
    $trail->push('Létrehozás', route('user.group.create'));
});

Breadcrumbs::for('userGroup.edit', function ($trail, $user) {
    $trail->parent('userGroup.index');
    $trail->push('Szerkesztés', route('user.group.edit', $user));
});

Breadcrumbs::for('userGroup.show', function ($trail, $user) {
    $trail->parent('userGroup.index');
    $trail->push('Profil', route('user.group.show', $user));
});

/***************************************************************************/

Breadcrumbs::for('permission.index', function ($trail) {
    $trail->parent('admin');
    $trail->push('Jogosultságok', route('permission.index'));
});

Breadcrumbs::for('permission.create', function ($trail) {
    $trail->parent('permission.index');
    $trail->push('Létrehozás', route('permission.create'));
});

Breadcrumbs::for('permission.edit', function ($trail, $user) {
    $trail->parent('permission.index');
    $trail->push('Szerkesztés', route('permission.edit', $user));
});

Breadcrumbs::for('permission.show', function ($trail, $user) {
    $trail->parent('permission.index');
    $trail->push('Profil', route('permission.show', $user));
});

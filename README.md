# User Package

This package provides user management functionality with both Filament and Inertia Vue admin interfaces.

## Requirements

- Laravel 11+
- **Laravel Sanctum ^4.0** - Required for API authentication and token management

## Features

- User management
- User groups with permissions
- Permission management
- ACL (Access Control List) system
- Dual admin interfaces:
  - Filament admin (backend)
  - Inertia Vue admin (frontend)

## Installation

The package is automatically loaded via the service provider.

### Laravel Sanctum Setup

This package requires Laravel Sanctum for API authentication. If not already installed:

1. Install Sanctum:
```bash
composer require laravel/sanctum
```

2. Publish Sanctum configuration and migrations:
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

3. Run migrations:
```bash
php artisan migrate
```

4. Add `HasApiTokens` trait to your User model:
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    // ...
}
```

5. Add Sanctum guard to `config/auth.php`:
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'sanctum' => [
        'driver' => 'sanctum',
        'provider' => 'users',
    ],
],
```

### Publishing Vue Components

To use the Inertia Vue admin, publish the Vue components:

```bash
php artisan vendor:publish --tag=user-views
```

Or manually copy:

```bash
cp -r packages/user/resources/js/* resources/js/pages/User/
```

## Routes

### API Authentication Routes

The package provides API authentication endpoints:

- `POST /api/auth/login` - User login (returns token)
- `POST /api/auth/logout` - User logout (requires authentication)
- `GET /api/auth/me` - Get authenticated user (requires authentication)

Example login request:
```json
{
  "email": "user@example.com",
  "password": "password",
  "device_name": "web_browser"
}
```

Example response:
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com",
    "email_verified_at": null,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  },
  "token": "1|abcdef123456...",
  "token_type": "Bearer"
}
```

### Admin Routes

The Inertia admin routes are available under `/admin/user`:

- `/admin/user/users` - User management
- `/admin/user/user-groups` - User group management
- `/admin/user/permissions` - Permission management

API admin routes (requires authentication):

- `GET /api/admin/user/users` - List users
- `POST /api/admin/user/users` - Create user
- `GET /api/admin/user/users/{id}` - Get user
- `PUT /api/admin/user/users/{id}` - Update user
- `DELETE /api/admin/user/users/{id}` - Delete user
- User groups and permissions have similar CRUD endpoints

## Usage

### Checking Permissions

Use the `acl` gate to check permissions:

```php
Gate::allows('acl', 'permission_name');
```

Or in Blade:

```blade
@can('acl', 'permission_name')
    // Content
@endcan
```

### ACL Service

```php
$acl = app('acl');
$acl->hasPermission('permission_name');
```

## Models

- `Molitor\User\Models\User` - User model
- `Molitor\User\Models\UserGroup` - User group model
- `Molitor\User\Models\Permission` - Permission model

## Admin Interfaces

### Filament Admin

The Filament resources are automatically registered:
- UserResource
- UserGroupResource
- PermissionResource

### Inertia Vue Admin

Vue components are located in `resources/js/pages/User/Admin/`:
- Users (Index, Create, Edit)
- UserGroups (Index, Create, Edit)
- Permissions (Index, Create, Edit)

All admin pages use the main AppLayout and require authentication.
 csomag

Laravel csomag felhasználók, felhasználói csoportok és jogosultságok (ACL) kezeléséhez. A csomag egyszerű csoport‑alapú jogosultságkezelést biztosít, migrációkkal, Gate integrációval és kényelmi szolgáltatásokkal.

## Fő funkciók

- Felhasználói csoportok (`UserGroup`) és jogosultságok (`Permission`) kezelése
- Tagságok kezelése (felhasználó ↔ csoport)
- Csoport ↔ jogosultság hozzárendelés
- Gate integráció: `Gate::allows('acl', 'permission-name')`
- `acl()` szolgáltatás a bejelentkezett felhasználó jogosultságainak gyors ellenőrzéséhez
- Kényelmi szerviz az admin műveletekhez: `AclManagementService`

## Telepítés
A csomag automatikusan regisztrálódik a szolgáltató révén.

### Menü regisztrálása

A felhasználói menü megjelenítéséhez regisztráld a `UserMenuBuilder`-t a `config/menu.php` fájlban:

```php
return [
    \Molitor\User\Services\UserMenuBuilder::class,
];
```

Ez automatikusan hozzáadja a felhasználók, csoportok és jogosultságok menüpontokat az admin menühöz.

### Seeder regisztrálása

A jogosultságok és csoportok kezdeti beállításához regisztráld a seedert a `DatabaseSeeder.php` fájlban:

```php

use Molitor\User\database\seeders\UserSeeder;

public function run(): void
{
    $this->call([
        // ...más seederek
        UserSeeder::class,
    ]);
}
```

### Fordítások

Fordítások betöltési névtere: `user`. Szükség esetén publikáld vagy használd közvetlenül a névtérrel.

## Használat

### Jogosultság ellenőrzése Gate-tel

```php
use Illuminate\Support\Facades\Gate;

if (Gate::allows('acl', 'orders.view')) {
    // van jogosultság
}
```

Több jogosultság esetén bármelyik megléte elegendő:

```php
Gate::allows('acl', ['orders.view', 'orders.edit']);
// vagy szóközzel elválasztva
Gate::allows('acl', 'orders.view orders.edit');
```

### `acl()` szolgáltatás (singleton)

```php
/** @var \Molitor\User\Services\Acl $acl */
$acl = app('acl');

if ($acl->hasPermission('orders.view')) {
    // ...
}

$permissions = $acl->getPermissions(); // string[]
```

Megjegyzés: a szolgáltatás a bejelentkezett felhasználóhoz tartozó jogosultságokat tölti be (auth alapú), és SQL-lekérdezéssel gyűjti össze a csoportokon keresztül.

### Admin/seed műveletek: `AclManagementService`

Kényelmi osztály jogosultságok, csoportok és felhasználók létrehozásához és összerendeléséhez.

```php
use Molitor\User\Services\AclManagementService;

public function seedAcl(AclManagementService $svc): void
{
    // Csoportok
    $svc->createUserGroup('admin', 'Rendszergazdák');
    $svc->createUserGroup('staff', 'Belső felhasználók', isDefault: true);

    // Jogosultságok
    $svc->createPermission('orders.view', 'Rendelések megtekintése', ['admin', 'staff']);
    $svc->createPermission('orders.edit', 'Rendelések szerkesztése', 'admin');

    // Felhasználó létrehozása és csoportba rakása
    $user = $svc->createUser('jane@example.com', 'Jane Doe', 'secret', ['admin']);

    // (Opcionális) egyedi hozzárendelés
    $svc->setUserGroupPermission('staff', 'products.view');
}
```

Elérhető főbb metódusok:

- `createUser(string $email, string $name, string $password, ?array $userGroups = null): User`
- `createPermission(string $name, string $description, array|string $userGroups): Permission`
- `createUserGroup(string $name, string $description, bool $isDefault = false): UserGroup`
- `setUserGroupPermission(string|UserGroup $userGroup, string|Permission $permission): self`
- `setDefaultUserGroups(User $user): self` – a defaultnak jelölt csoportokba belépteti a felhasználót

## Modellek és kapcsolatok

- `Molitor\User\Models\User` – az `App\Models\User` kiterjesztése, extra kapcsolat:
  - `userGroups(): BelongsToMany` – kapcsolódás a `memberships` pivot táblán
- `Molitor\User\Models\UserGroup`
  - Fillable: `name`, `description`, `is_default`
  - `permissions(): BelongsToMany` – `user_group_permissions` pivot
- `Molitor\User\Models\Permission`
  - Fillable: `name`, `description`
  - `userGroups(): BelongsToMany` – `user_group_permissions` pivot

## Gate és szolgáltató

A `Molitor\User\Providers\UserServiceProvider`:

- Betölti a migrációkat: `src/database/migrations`
- Betölti a fordításokat: `resources/lang` névtér: `user`
- Regisztrálja a `Gate::define('acl', ...)` ellenőrzést
- Regisztrál egy `acl` nevű singletront (`Molitor\User\Services\Acl`)
- Bindolja a repository interfészeket implementációkra

## Repository interfészek (részlet)

- `UserRepositoryInterface`
  - `getByEmail(string $email): ?User`
  - `getAll(): Collection`
  - `delete(User $user): bool`
  - `create(string $name, string $email, string $password): User`

Hasonló módon elérhető: `UserGroupRepositoryInterface`, `PermissionRepositoryInterface`, `MembershipRepositoryInterface`, `UserGroupPermissionRepositoryInterface`.

## Események

- `Molitor\User\Events\UserLoginEvent` – bejelentkezéshez kapcsolódó esemény (ha használva van az alkalmazáson belül).

## Tippek

- Jogosultság ellenőrzésnél az első megtalált jogosultság elég: ha több nevet adsz át, bármelyik teljesülése `true`-t ad.
- Ha egy jogosultság vagy csoport nem létezik, az admin szolgáltatás létre is tudja hozni (pl. `setUserGroupPermission` létrehozza a hiányzó elemeket szükség esetén).
- Új felhasználó létrehozásakor, ha nem adsz meg csoportokat, a defaultnak jelöltekbe kerül.

## Licenc

MIT

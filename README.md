# User csomag

Laravel csomag felhasználók, felhasználói csoportok és jogosultságok (ACL) kezeléséhez. A csomag egyszerű csoport‑alapú jogosultságkezelést biztosít, migrációkkal, Gate integrációval és kényelmi szolgáltatásokkal.

## Fő funkciók

- Felhasználói csoportok (`UserGroup`) és jogosultságok (`Permission`) kezelése
- Tagságok kezelése (felhasználó ↔ csoport)
- Csoport ↔ jogosultság hozzárendelés
- Gate integráció: `Gate::allows('acl', 'permission-name')`
- `acl()` szolgáltatás a bejelentkezett felhasználó jogosultságainak gyors ellenőrzéséhez
- Kényelmi szerviz az admin műveletekhez: `AclManagementService`

## Telepítés

Monorepóban a csomag path repository-ként van bekötve, és a root `composer.json` már hivatkozik rá. Külső projektben a telepítés:

```bash
composer require istvanmolitor/user
```

A csomag Laravel Package Auto-Discovery-t használ, a `UserServiceProvider` automatikusan regisztrálódik.

### Migrációk futtatása

```bash
php artisan migrate
```

Betöltött migrációk (táblák):

- `user_groups` – csoportok
- `permissions` – jogosultságok
- `memberships` – felhasználó ↔ csoport kapcsoló tábla
- `user_group_permissions` – csoport ↔ jogosultság kapcsoló tábla

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

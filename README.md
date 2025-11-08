# User modul

A modul kezeli a felhasználókat. a felhasználói csoportokat, és a jogosultságokat.

## Előfeltételek

Telepíteni kell az admin modult.: https://gitlab.com/molitor/admin

## Telepítés

### Provider regisztrálása
config/app.php
```php
'providers' => ServiceProvider::defaultProviders()->merge([
    /*
    * Package Service Providers...
    */
    \Molitor\User\Providers\UserServiceProvider::class,
])->toArray(),
```

### Middleware regisztrálása
app/Http/Kernel.php
```php
use Molitor\User\Http\Middleware\PermissionMiddleware;

protected $middlewareAliases = [
    'permission' => PermissionMiddleware::class,
];
```

### Seeder regisztrálása

database/seeders/DatabaseSeeder.php
```php

use Molitor\User\database\seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
    }
}
```

### Menüpont megjelenítése az admin menüben

Ma a Menü modul telepítve van akkor meg lehet jeleníteni az admin menüben.

```php
<?php
//Menü builderek listája:
return [
    \Molitor\User\Services\Menu\UserMenuBuilder::class
];
```

### Breadcrumb telepítése

A user modul breadcrumbs.php fileját regisztrálni kell a configs/breadcrumbs.php fileban. 
```php
<?php
'files' => [
    base_path('/vendor/molitor/user/src/routes/breadcrumbs.php'),
],
```

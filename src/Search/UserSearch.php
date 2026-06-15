<?php

declare(strict_types=1);

namespace Molitor\User\Search;

use Molitor\Admin\Search\AdminSearch;
use Molitor\Admin\Search\AdminSearchResults;
use Molitor\User\Models\User;

class UserSearch extends AdminSearch
{
    public function search(string $q, int $limit, AdminSearchResults $results): void
    {
        $this->filter(User::query(), $q, ['name', 'email'])
            ->limit($limit)
            ->get()
            ->each(fn (User $user) => $results->addResult(
                type: 'user',
                typeLabel: 'Felhasználó',
                id: $user->id,
                title: $user->name ?? $user->email,
                subtitle: $user->email,
                url: "/admin/user/users/{$user->id}",
            ));
    }
}

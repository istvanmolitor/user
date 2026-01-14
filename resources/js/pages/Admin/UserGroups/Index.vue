<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { ref } from 'vue';
import { trans } from 'laravel-vue-i18n';
import { route } from '@/lib/route';

interface UserGroup {
    id: number;
    name: string;
    description: string | null;
    is_default: boolean;
    permissions: Array<{ id: number; name: string }>;
}

interface Props {
    userGroups: {
        data: UserGroup[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    filters: {
        search?: string;
        sort?: string;
        direction?: 'asc' | 'desc';
    };
}

const props = defineProps<Props>();

const search = ref(props.filters.search || '');

// Translation helper
const t = (key: string) => trans(key);

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('user::common.admin'), href: '#' },
    { title: t('user::common.user_groups'), href: route('user.admin.user-groups.index') },
];

const handleSearch = () => {
    router.get(route('user.admin.user-groups.index'), {
        search: search.value,
        sort: props.filters.sort,
        direction: props.filters.direction,
    }, {
        preserveState: true,
        replace: true,
    });
};

const sortBy = (column: string) => {
    let direction: 'asc' | 'desc' = 'asc';
    if (props.filters.sort === column && props.filters.direction === 'asc') {
        direction = 'desc';
    }
    router.get(route('user.admin.user-groups.index'), {
        search: search.value,
        sort: column,
        direction: direction,
    }, {
        preserveState: true,
        replace: true,
    });
};

const getSortIcon = (column: string) => {
    if (props.filters.sort !== column) return '↕️';
    return props.filters.direction === 'asc' ? '↑' : '↓';
};

const deleteUserGroup = (userGroupId: number) => {
    if (confirm(t('user::user-group.messages.confirm_delete'))) {
        router.delete(route('user.admin.user-groups.destroy', userGroupId));
    }
};
</script>

<template>
    <Head title="User Groups - Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ t('user::user-group.title') }}</h1>
                <Link :href="route('user.admin.user-groups.create')">
                    <Button>{{ t('user::user-group.actions.create_user_group') }}</Button>
                </Link>
            </div>

            <div class="flex gap-2">
                <Input
                    v-model="search"
                    type="text"
                    :placeholder="t('user::user-group.placeholders.search')"
                    class="max-w-sm"
                    @keyup.enter="handleSearch"
                />
                <Button @click="handleSearch">{{ t('user::user-group.actions.search') }}</Button>
            </div>

            <div class="rounded-lg border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="cursor-pointer select-none" @click="sortBy('name')">
                                {{ t('user::user-group.table.name') }} {{ getSortIcon('name') }}
                            </TableHead>
                            <TableHead class="cursor-pointer select-none" @click="sortBy('description')">
                                {{ t('user::user-group.table.description') }} {{ getSortIcon('description') }}
                            </TableHead>
                            <TableHead>{{ t('user::user-group.table.permissions') }}</TableHead>
                            <TableHead class="cursor-pointer select-none" @click="sortBy('is_default')">
                                {{ t('user::user-group.table.default') }} {{ getSortIcon('is_default') }}
                            </TableHead>
                            <TableHead class="text-right">{{ t('user::user-group.table.actions') }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="group in userGroups.data" :key="group.id">
                            <TableCell class="font-medium">{{ group.name }}</TableCell>
                            <TableCell>{{ group.description || '-' }}</TableCell>
                            <TableCell>
                                <div class="flex flex-wrap gap-1">
                                    <Badge v-for="permission in group.permissions" :key="permission.id" variant="secondary">
                                        {{ permission.name }}
                                    </Badge>
                                    <span v-if="group.permissions.length === 0" class="text-sm text-muted-foreground">
                                        {{ t('user::user-group.messages.no_permissions') }}
                                    </span>
                                </div>
                            </TableCell>
                            <TableCell>
                                <Badge :variant="group.is_default ? 'default' : 'outline'">
                                    {{ group.is_default ? trans('user::user-group.values.yes') : trans('user::user-group.values.no') }}
                                </Badge>
                            </TableCell>
                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <Link :href="route('user.admin.user-groups.edit', group.id)">
                                        <Button variant="outline" size="sm">{{ t('user::user-group.actions.edit') }}</Button>
                                    </Link>
                                    <Button variant="destructive" size="sm" @click="deleteUserGroup(group.id)">
                                        {{ t('user::user-group.actions.delete') }}
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div v-if="userGroups.last_page > 1" class="flex justify-center gap-2">
                <Button
                    v-for="page in userGroups.last_page"
                    :key="page"
                    :variant="page === userGroups.current_page ? 'default' : 'outline'"
                    size="sm"
                    @click="router.get(route('user.admin.user-groups.index'), { page, search: search })"
                >
                    {{ page }}
                </Button>
            </div>
        </div>
    </AppLayout>
</template>


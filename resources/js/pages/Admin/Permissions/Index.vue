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

interface Permission {
    id: number;
    name: string;
    description: string | null;
    user_groups: Array<{ id: number; name: string }>;
}

interface Props {
    permissions: {
        data: Permission[];
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

// Translation helper for templates
const t = (key: string) => trans(key);

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('user::common.admin'), href: '#' },
    { title: t('user::common.permissions'), href: route('user.admin.permissions.index') },
];

const handleSearch = () => {
    router.get(route('user.admin.permissions.index'), {
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
    router.get(route('user.admin.permissions.index'), {
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

const deletePermission = (permissionId: number) => {
    if (confirm(t('user::permission.messages.confirm_delete'))) {
        router.delete(route('user.admin.permissions.destroy', permissionId));
    }
};
</script>

<template>
    <Head :title="t('user::permission.title') + ' - ' + t('user::common.admin')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ t('user::permission.title') }}</h1>
                <Link :href="route('user.admin.permissions.create')">
                    <Button>{{ t('user::permission.actions.create_permission') }}</Button>
                </Link>
            </div>

            <div class="flex gap-2">
                <Input
                    v-model="search"
                    type="text"
                    :placeholder="t('user::permission.placeholders.search')"
                    class="max-w-sm"
                    @keyup.enter="handleSearch"
                />
                <Button @click="handleSearch">{{ t('user::permission.actions.search') }}</Button>
            </div>

            <div class="rounded-lg border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="cursor-pointer select-none" @click="sortBy('name')">
                                {{ t('user::permission.table.name') }} {{ getSortIcon('name') }}
                            </TableHead>
                            <TableHead class="cursor-pointer select-none" @click="sortBy('description')">
                                {{ t('user::permission.table.description') }} {{ getSortIcon('description') }}
                            </TableHead>
                            <TableHead>{{ t('user::permission.table.userGroups') }}</TableHead>
                            <TableHead class="text-right">{{ t('user::permission.table.actions') }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="permission in permissions.data" :key="permission.id">
                            <TableCell class="font-medium">{{ permission.name }}</TableCell>
                            <TableCell>{{ permission.description || '-' }}</TableCell>
                            <TableCell>
                                <div class="flex flex-wrap gap-1">
                                    <Badge v-for="group in permission.user_groups" :key="group.id" variant="secondary">
                                        {{ group.name }}
                                    </Badge>
                                    <span v-if="permission.user_groups.length === 0" class="text-sm text-muted-foreground">
                                        {{ t('user::permission.messages.no_groups') }}
                                    </span>
                                </div>
                            </TableCell>
                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <Link :href="route('user.admin.permissions.edit', permission.id)">
                                        <Button variant="outline" size="sm">{{ t('user::permission.actions.edit') }}</Button>
                                    </Link>
                                    <Button variant="destructive" size="sm" @click="deletePermission(permission.id)">
                                        {{ t('user::permission.actions.delete') }}
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div v-if="permissions.last_page > 1" class="flex justify-center gap-2">
                <Button
                    v-for="page in permissions.last_page"
                    :key="page"
                    :variant="page === permissions.current_page ? 'default' : 'outline'"
                    size="sm"
                    @click="router.get(route('user.admin.permissions.index'), { page, search: search })"
                >
                    {{ page }}
                </Button>
            </div>
        </div>
    </AppLayout>
</template>


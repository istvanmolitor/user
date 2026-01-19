<script setup lang="ts">
import AppLayout from '@admin/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@admin/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@admin/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@admin/components/ui/table';
import { Badge } from '@admin/components/ui/badge';
import { trans } from 'laravel-vue-i18n';
import { route } from '@admin/lib/route';
import { AdminFilter, AdminPagination, AdminDeleteButton } from '@admin/components';
import { useAdminSort } from '@admin/composables/useAdminSort';
import Icon from '@admin/components/Icon.vue';

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

const { sortBy, getSortIcon } = useAdminSort('user.admin.user-groups.index', props);

// Translation helper
const t = (key: string) => trans(key);

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('user::common.admin'), href: '#' },
    { title: t('user::common.user_groups'), href: route('user.admin.user-groups.index') },
];
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

            <AdminFilter
                route-name="user.admin.user-groups.index"
                :filters="filters"
                :placeholder="t('user::user-group.placeholders.search')"
            />

            <div class="rounded-lg border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="cursor-pointer select-none" @click="sortBy('name')">
                                <div class="flex items-center gap-1">
                                    {{ t('user::user-group.table.name') }}
                                    <Icon :name="getSortIcon('name')" class="h-4 w-4" />
                                </div>
                            </TableHead>
                            <TableHead class="cursor-pointer select-none" @click="sortBy('description')">
                                <div class="flex items-center gap-1">
                                    {{ t('user::user-group.table.description') }}
                                    <Icon :name="getSortIcon('description')" class="h-4 w-4" />
                                </div>
                            </TableHead>
                            <TableHead>{{ t('user::user-group.table.permissions') }}</TableHead>
                            <TableHead class="cursor-pointer select-none" @click="sortBy('is_default')">
                                <div class="flex items-center gap-1">
                                    {{ t('user::user-group.table.default') }}
                                    <Icon :name="getSortIcon('is_default')" class="h-4 w-4" />
                                </div>
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
                                    <AdminDeleteButton
                                        :url="route('user.admin.user-groups.destroy', group.id)"
                                        :message="t('user::user-group.messages.confirm_delete')"
                                        :button-text="t('user::user-group.actions.delete')"
                                    />
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <AdminPagination
                route-name="user.admin.user-groups.index"
                :data="userGroups"
                :filters="filters"
            />
        </div>
    </AppLayout>
</template>


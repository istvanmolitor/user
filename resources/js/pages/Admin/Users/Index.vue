<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { trans } from 'laravel-vue-i18n';
import { route } from '@/lib/route';
import { AdminFilter } from '@admin/components';
import { useAdminSort } from '@admin/composables/useAdminSort';
import Icon from '@/components/Icon.vue';

interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    user_groups: Array<{ id: number; name: string }>;
}

interface Props {
    users: {
        data: User[];
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

const { sortBy, getSortIcon } = useAdminSort('user.admin.users.index', props);

// Translation helper
const t = (key: string) => trans(key);

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('user::common.admin'), href: '#' },
    { title: t('user::common.users'), href: route('user.admin.users.index') },
];

const deleteUser = (userId: number) => {
    if (confirm(t('user::user.messages.confirm_delete'))) {
        router.delete(route('user.admin.users.destroy', userId));
    }
};
</script>

<template>
    <Head
        :title="t('user::user.title') + ' - ' + trans('user::common.admin')"
    />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ t('user::user.title') }}</h1>
                <Link :href="route('user.admin.users.create')">
                    <Button>{{ t('user::user.actions.create_user') }}</Button>
                </Link>
            </div>

            <AdminFilter
                route-name="user.admin.users.index"
                :filters="filters"
                :placeholder="t('user::user.placeholders.search')"
            />

            <div class="rounded-lg border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead
                                class="cursor-pointer select-none"
                                @click="sortBy('name')"
                            >
                                <div class="flex items-center gap-1">
                                    {{ t('user::user.table.name') }}
                                    <Icon
                                        :name="getSortIcon('name')"
                                        class="h-4 w-4"
                                    />
                                </div>
                            </TableHead>
                            <TableHead
                                class="cursor-pointer select-none"
                                @click="sortBy('email')"
                            >
                                <div class="flex items-center gap-1">
                                    {{ t('user::user.table.email') }}
                                    <Icon
                                        :name="getSortIcon('email')"
                                        class="h-4 w-4"
                                    />
                                </div>
                            </TableHead>
                            <TableHead>{{
                                t('user::user.table.user_groups')
                            }}</TableHead>
                            <TableHead>{{
                                t('user::user.table.verified')
                            }}</TableHead>
                            <TableHead class="text-right">{{
                                t('user::user.table.actions')
                            }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in users.data" :key="user.id">
                            <TableCell class="font-medium">{{
                                user.name
                            }}</TableCell>
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell>
                                <div class="flex gap-1">
                                    <Badge
                                        v-for="group in user.user_groups"
                                        :key="group.id"
                                        variant="secondary"
                                    >
                                        {{ group.name }}
                                    </Badge>
                                </div>
                            </TableCell>
                            <TableCell>
                                <Badge
                                    :variant="
                                        user.email_verified_at
                                            ? 'default'
                                            : 'outline'
                                    "
                                >
                                    {{
                                        user.email_verified_at
                                            ? trans('user::user.values.yes')
                                            : trans('user::user.values.no')
                                    }}
                                </Badge>
                            </TableCell>
                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <Link
                                        :href="
                                            route(
                                                'user.admin.users.edit',
                                                user.id,
                                            )
                                        "
                                    >
                                        <Button variant="outline" size="sm">{{
                                            t('user::user.actions.edit')
                                        }}</Button>
                                    </Link>
                                    <Button
                                        variant="destructive"
                                        size="sm"
                                        @click="deleteUser(user.id)"
                                    >
                                        {{ t('user::user.actions.delete') }}
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div v-if="users.last_page > 1" class="flex justify-center gap-2">
                <Button
                    v-for="page in users.last_page"
                    :key="page"
                    :variant="
                        page === users.current_page ? 'default' : 'outline'
                    "
                    size="sm"
                    @click="
                        router.get(route('user.admin.users.index'), {
                            page,
                            ...filters,
                        })
                    "
                >
                    {{ page }}
                </Button>
            </div>
        </div>
    </AppLayout>
</template>

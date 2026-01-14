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
    };
}

const props = defineProps<Props>();

const search = ref(props.filters.search || '');

// Translation helper
const t = (key: string) => trans(key);

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('user::common.admin'), href: '#' },
    { title: t('user::common.users'), href: route('user.admin.users.index') },
];

const handleSearch = () => {
    router.get(route('user.admin.users.index'), { search: search.value }, {
        preserveState: true,
        replace: true,
    });
};

const deleteUser = (userId: number) => {
    if (confirm(t('user::user.messages.confirm_delete'))) {
        router.delete(route('user.admin.users.destroy', userId));
    }
};
</script>

<template>
    <Head :title="t('user::user.title') + ' - ' + trans('user::common.admin')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ t('user::user.title') }}</h1>
                <Link :href="route('user.admin.users.create')">
                    <Button>{{ t('user::user.actions.create_user') }}</Button>
                </Link>
            </div>

            <div class="flex gap-2">
                <Input
                    v-model="search"
                    type="text"
                    :placeholder="t('user::user.placeholders.search')"
                    class="max-w-sm"
                    @keyup.enter="handleSearch"
                />
                <Button @click="handleSearch">{{ t('user::user.actions.search') }}</Button>
            </div>

            <div class="rounded-lg border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>{{ t('user::user.table.name') }}</TableHead>
                            <TableHead>{{ t('user::user.table.email') }}</TableHead>
                            <TableHead>{{ t('user::user.table.user_groups') }}</TableHead>
                            <TableHead>{{ t('user::user.table.verified') }}</TableHead>
                            <TableHead class="text-right">{{ t('user::user.table.actions') }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in users.data" :key="user.id">
                            <TableCell class="font-medium">{{ user.name }}</TableCell>
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell>
                                <div class="flex gap-1">
                                    <Badge v-for="group in user.user_groups" :key="group.id" variant="secondary">
                                        {{ group.name }}
                                    </Badge>
                                </div>
                            </TableCell>
                            <TableCell>
                                <Badge :variant="user.email_verified_at ? 'default' : 'outline'">
                                    {{ user.email_verified_at ? trans('user::user.values.yes') : trans('user::user.values.no') }}
                                </Badge>
                            </TableCell>
                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <Link :href="route('user.admin.users.edit', user.id)">
                                        <Button variant="outline" size="sm">{{ t('user::user.actions.edit') }}</Button>
                                    </Link>
                                    <Button variant="destructive" size="sm" @click="deleteUser(user.id)">
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
                    :variant="page === users.current_page ? 'default' : 'outline'"
                    size="sm"
                    @click="router.get(route('user.admin.users.index'), { page, search: search })"
                >
                    {{ page }}
                </Button>
            </div>
        </div>
    </AppLayout>
</template>


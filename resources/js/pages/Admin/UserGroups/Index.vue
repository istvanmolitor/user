<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { ref } from 'vue';

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
    };
}

const props = defineProps<Props>();

const search = ref(props.filters.search || '');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'User Groups', href: route('user.admin.user-groups.index') },
];

const handleSearch = () => {
    router.get(route('user.admin.user-groups.index'), { search: search.value }, {
        preserveState: true,
        replace: true,
    });
};

const deleteUserGroup = (userGroupId: number) => {
    if (confirm('Are you sure you want to delete this user group?')) {
        router.delete(route('user.admin.user-groups.destroy', userGroupId));
    }
};
</script>

<template>
    <Head title="User Groups - Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">User Groups</h1>
                <Link :href="route('user.admin.user-groups.create')">
                    <Button>Create User Group</Button>
                </Link>
            </div>

            <div class="flex gap-2">
                <Input
                    v-model="search"
                    type="text"
                    placeholder="Search user groups..."
                    class="max-w-sm"
                    @keyup.enter="handleSearch"
                />
                <Button @click="handleSearch">Search</Button>
            </div>

            <div class="rounded-lg border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Description</TableHead>
                            <TableHead>Permissions</TableHead>
                            <TableHead>Default</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
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
                                        No permissions
                                    </span>
                                </div>
                            </TableCell>
                            <TableCell>
                                <Badge :variant="group.is_default ? 'default' : 'outline'">
                                    {{ group.is_default ? 'Yes' : 'No' }}
                                </Badge>
                            </TableCell>
                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <Link :href="route('user.admin.user-groups.edit', group.id)">
                                        <Button variant="outline" size="sm">Edit</Button>
                                    </Link>
                                    <Button variant="destructive" size="sm" @click="deleteUserGroup(group.id)">
                                        Delete
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


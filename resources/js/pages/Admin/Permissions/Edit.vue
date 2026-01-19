<script setup lang="ts">
import AppLayout from '@admin/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@admin/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Button } from '@admin/components/ui/button';
import { Input } from '@admin/components/ui/input';
import { Label } from '@admin/components/ui/label';
import { Textarea } from '@admin/components/ui/textarea';
import InputError from '@admin/components/InputError.vue';
import { Badge } from '@admin/components/ui/badge';

interface Permission {
    id: number;
    name: string;
    description: string | null;
    user_groups: Array<{ id: number; name: string }>;
}

interface Props {
    permission: Permission;
}

const props = defineProps<Props>();

const form = useForm({
    name: props.permission.name,
    description: props.permission.description || '',
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'Permissions', href: route('user.admin.permissions.index') },
    { title: 'Edit', href: route('user.admin.permissions.edit', props.permission.id) },
];

const submit = () => {
    form.put(route('user.admin.permissions.update', props.permission.id));
};
</script>

<template>
    <Head title="Edit Permission - Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Edit Permission</h1>

            <form @submit.prevent="submit" class="max-w-2xl space-y-6">
                <div class="space-y-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="space-y-2">
                    <Label for="description">Description</Label>
                    <Textarea
                        id="description"
                        v-model="form.description"
                        rows="3"
                    />
                    <InputError :message="form.errors.description" />
                </div>

                <div v-if="permission.user_groups.length > 0" class="space-y-2">
                    <Label>Assigned to User Groups</Label>
                    <div class="flex flex-wrap gap-2">
                        <Badge v-for="group in permission.user_groups" :key="group.id" variant="secondary">
                            {{ group.name }}
                        </Badge>
                    </div>
                </div>

                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">
                        Update Permission
                    </Button>
                    <Button
                        type="button"
                        variant="outline"
                        @click="router.visit(route('user.admin.permissions.index'))"
                    >
                        Cancel
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>


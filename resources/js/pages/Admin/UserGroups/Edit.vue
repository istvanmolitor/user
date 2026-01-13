<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import InputError from '@/components/InputError.vue';

interface UserGroup {
    id: number;
    name: string;
    description: string | null;
    is_default: boolean;
    permissions: Array<{ id: number; name: string }>;
}

interface Permission {
    id: number;
    name: string;
    description: string | null;
}

interface Props {
    userGroup: UserGroup;
    permissions: Permission[];
}

const props = defineProps<Props>();

const form = useForm({
    name: props.userGroup.name,
    description: props.userGroup.description || '',
    is_default: props.userGroup.is_default,
    permissions: props.userGroup.permissions.map(p => p.id),
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'User Groups', href: route('user.admin.user-groups.index') },
    { title: 'Edit', href: route('user.admin.user-groups.edit', props.userGroup.id) },
];

const submit = () => {
    form.put(route('user.admin.user-groups.update', props.userGroup.id));
};

const togglePermission = (permissionId: number) => {
    const index = form.permissions.indexOf(permissionId);
    if (index > -1) {
        form.permissions.splice(index, 1);
    } else {
        form.permissions.push(permissionId);
    }
};
</script>

<template>
    <Head title="Edit User Group - Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Edit User Group</h1>

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

                <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <Checkbox
                            id="is_default"
                            :checked="form.is_default"
                            @update:checked="form.is_default = $event"
                        />
                        <Label for="is_default">Default Group</Label>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        New users will be automatically assigned to this group
                    </p>
                </div>

                <div class="space-y-2">
                    <Label>Permissions</Label>
                    <div class="space-y-2">
                        <div
                            v-for="permission in permissions"
                            :key="permission.id"
                            class="flex items-start space-x-2"
                        >
                            <Checkbox
                                :id="`permission-${permission.id}`"
                                :checked="form.permissions.includes(permission.id)"
                                @update:checked="togglePermission(permission.id)"
                            />
                            <div class="flex-1">
                                <Label :for="`permission-${permission.id}`">{{ permission.name }}</Label>
                                <p v-if="permission.description" class="text-sm text-muted-foreground">
                                    {{ permission.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <InputError :message="form.errors.permissions" />
                </div>

                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">
                        Update User Group
                    </Button>
                    <Button
                        type="button"
                        variant="outline"
                        @click="router.visit(route('user.admin.user-groups.index'))"
                    >
                        Cancel
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>


<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import InputError from '@/components/InputError.vue';

interface UserGroup {
    id: number;
    name: string;
}

interface Props {
    userGroups: UserGroup[];
}

const props = defineProps<Props>();

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    user_groups: [] as number[],
    email_verified: false,
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'Users', href: route('user.admin.users.index') },
    { title: 'Create', href: route('user.admin.users.create') },
];

const submit = () => {
    form.post(route('user.admin.users.store'));
};

const toggleUserGroup = (groupId: number) => {
    const index = form.user_groups.indexOf(groupId);
    if (index > -1) {
        form.user_groups.splice(index, 1);
    } else {
        form.user_groups.push(groupId);
    }
};
</script>

<template>
    <Head title="Create User - Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">Create User</h1>

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
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="space-y-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        v-model="form.password"
                        type="password"
                        required
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="space-y-2">
                    <Label for="password_confirmation">Confirm Password</Label>
                    <Input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        required
                    />
                </div>

                <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <Checkbox
                            id="email_verified"
                            :checked="form.email_verified"
                            @update:checked="form.email_verified = $event"
                        />
                        <Label for="email_verified">Email Verified</Label>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label>User Groups</Label>
                    <div class="space-y-2">
                        <div
                            v-for="group in userGroups"
                            :key="group.id"
                            class="flex items-center space-x-2"
                        >
                            <Checkbox
                                :id="`group-${group.id}`"
                                :checked="form.user_groups.includes(group.id)"
                                @update:checked="toggleUserGroup(group.id)"
                            />
                            <Label :for="`group-${group.id}`">{{ group.name }}</Label>
                        </div>
                    </div>
                    <InputError :message="form.errors.user_groups" />
                </div>

                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">
                        Create User
                    </Button>
                    <Button
                        type="button"
                        variant="outline"
                        @click="router.visit(route('user.admin.users.index'))"
                    >
                        Cancel
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>


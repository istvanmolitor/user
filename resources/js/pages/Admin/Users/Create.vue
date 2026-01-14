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
    user_groups: [] as number[],
    email_verified: false,
});

// Translation helper
const t = (key: string) => trans(key);

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('user::common.admin'), href: '#' },
    { title: t('user::common.users'), href: route('user.admin.users.index') },
    { title: t('user::user.create'), href: route('user.admin.users.create') },
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
    <Head :title="t('user::user.create') + ' - ' + trans('user::common.admin')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <h1 class="text-2xl font-bold">{{ t('user::user.create') }}</h1>

            <form @submit.prevent="submit" class="max-w-2xl space-y-6">
                <div class="space-y-2">
                    <Label for="name">{{ t('user::user.form.name') }}</Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="space-y-2">
                    <Label for="email">{{ t('user::user.form.email') }}</Label>
                    <Input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <Checkbox
                            id="email_verified"
                            :checked="form.email_verified"
                            @update:checked="form.email_verified = $event"
                        />
                        <Label for="email_verified">{{ t('user::user.form.email_verified') }}</Label>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label>{{ t('user::user.form.user_groups') }}</Label>
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
                        {{ t('user::user.actions.create_user') }}
                    </Button>
                    <Button
                        type="button"
                        variant="outline"
                        @click="router.visit(route('user.admin.users.index'))"
                    >
                        {{ t('user::user.actions.cancel') }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>


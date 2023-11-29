<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import { notify } from "notiwind";
import { NotificationGroup, Notification } from 'notiwind';

const props = defineProps({
    permissions: {
        type: Array
    }
});

const showModal = ref(false);

const form = useForm({
    permission_name: ''
});

const permissionID = ref(null);

const editPermmisionName = (permission) => {
    showModal.value = true;
    permissionID.value = permission.id;
    form.permission_name = permission.name;
};

const savePermissionName = async () => {
    try {
        const response = await axios.patch(route('admin.edit.permission', { id: permissionID.value }), {
            permission_name: form.permission_name
        });
        showModal.value = false;
        window.location.reload();
        if (response.status === 200) {
            notify({
                group: "success",
                title: "Success",
                text: "Permission name has successfully saved"
            }, 4000);
        }
        else {
            notify({
                group: "error",
                title: "Error",
                text: "Error change permission name"
            }, 4000);
        }
    } catch (error) {
        console.error('Error updating permissions name:', error);
        notify({
            group: "error",
            title: "Error",
            text: "Error update permission name"
        }, 4000);
    }

};

</script>

<template>
    <div>

        <Head title="List All Permissions" />
        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">List All Permissions</h2>
            </template>
            <NotificationGroup group="success">
                <div class="fixed inset-0 flex items-start justify-end p-6 px-4 py-6 pointer-events-none">
                    <div class="w-full max-w-sm">
                        <Notification v-slot="{ notifications }" enter="transform ease-out duration-300 transition"
                            enter-from="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
                            enter-to="translate-y-0 opacity-100 sm:translate-x-0" leave="transition ease-in duration-500"
                            leave-from="opacity-100" leave-to="opacity-0" move="transition duration-500"
                            move-delay="delay-300">
                            <div class="flex w-full max-w-sm mx-auto mt-4 overflow-hidden bg-white rounded-lg shadow-md"
                                v-for="notification in notifications" :key="notification.id">
                                <div class="flex items-center justify-center w-12 bg-green-500">
                                    <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z" />
                                    </svg>
                                </div>

                                <div class="px-4 py-2 -mx-3">
                                    <div class="mx-3">
                                        <span class="font-semibold text-green-500">{{ notification.title }}</span>
                                        <p class="text-sm text-gray-600">{{ notification.text }}</p>
                                    </div>
                                </div>
                            </div>
                        </Notification>
                    </div>
                </div>
            </NotificationGroup>
            <NotificationGroup group="error">
                <div class="fixed inset-0 flex items-start justify-end p-6 px-4 py-6 pointer-events-none">
                    <div class="w-full max-w-sm">
                        <Notification v-slot="{ notifications }" enter="transform ease-out duration-300 transition"
                            enter-from="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
                            enter-to="translate-y-0 opacity-100 sm:translate-x-0" leave="transition ease-in duration-500"
                            leave-from="opacity-100" leave-to="opacity-0" move="transition duration-500"
                            move-delay="delay-300">
                            <div class="flex w-full max-w-sm mx-auto mt-4 overflow-hidden bg-white rounded-lg shadow-md"
                                v-for="notification in notifications" :key="notification.id">
                                <div class="flex items-center justify-center w-12 bg-red-500">
                                    <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M20 3.36667C10.8167 3.36667 3.3667 10.8167 3.3667 20C3.3667 29.1833 10.8167 36.6333 20 36.6333C29.1834 36.6333 36.6334 29.1833 36.6334 20C36.6334 10.8167 29.1834 3.36667 20 3.36667ZM19.1334 33.3333V22.9H13.3334L21.6667 6.66667V17.1H27.25L19.1334 33.3333Z" />
                                    </svg>
                                </div>

                                <div class="px-4 py-2 -mx-3">
                                    <div class="mx-3">
                                        <span class="font-semibold text-red-500">{{ notification.title }}</span>
                                        <p class="text-sm text-gray-600">{{ notification.text }}</p>
                                    </div>
                                </div>
                            </div>
                        </Notification>
                    </div>
                </div>
            </NotificationGroup>
            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="overflow-x-auto bg-white border rounded-md shadow-md">
                        <div class="p-6 text-gray-900">
                            <table class="min-w-full overflow-hidden">
                                <thead class="bg-gray-100 border-b">
                                    <tr>
                                        <th class="p-4 text-center">#</th>
                                        <th class="p-4 text-center">Permission Name</th>
                                        <th class="p-4 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(permission, index) in permissions" :key="permission.id" class="border-b">
                                        <td class="p-4 text-center">{{ index + 1 }}</td>
                                        <td class="p-4 font-bold text-center text-blue-700">{{ permission.name }}</td>
                                        <td class="p-4 text-center">
                                            <PrimaryButton @click="editPermmisionName(permission)">
                                                Edit
                                            </PrimaryButton>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center">
                    <div class="fixed inset-0 transition-opacity" @click="showModal = false">
                        <div class="absolute inset-0 bg-black opacity-50"></div>
                    </div>
                    <div class="relative p-10 py-5 bg-white rounded-lg shadow-xl">
                        <form @submit.prevent="submit">
                            <h2 class="mb-4 font-bold text-blue-600 uppercase">
                                Edit Permission ID : {{ permissionID }}
                            </h2>
                            <div class="mb-6">
                                <InputLabel for="permission_name" value="Permission Name" />
                                <TextInput id="permission_name" type="text" v-model="form.permission_name"
                                    :disabled="form.processing"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-teal-500" />
                                <InputError :message="form.errors.permission_name" class="mt-2 text-red-500" />
                            </div>

                            <div class="flex justify-center">
                                <PrimaryButton @click="savePermissionName()"
                                    class="px-4 py-2 text-white bg-teal-500 rounded-full cursor-pointer">
                                    Update Permission
                                </PrimaryButton>
                            </div>
                            <button class="absolute text-gray-600 top-4 right-4 hover:text-gray-800"
                                @click="showModal = false">
                                X
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>

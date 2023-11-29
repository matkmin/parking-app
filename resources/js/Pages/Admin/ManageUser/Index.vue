<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    users: {
        type: Array
    }
});
</script>

<template>
    <div>
        <Head title="List User" />
        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">List Users</h2>
            </template>
            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="overflow-x-auto bg-white border rounded-md shadow-md">
                        <div class="p-6 text-gray-900">
                            <table class="min-w-full overflow-hidden">
                                <thead class="bg-gray-100 border-b">
                                    <tr>
                                        <th class="p-4 text-center">#</th>
                                        <th class="p-4 text-center">Name</th>
                                        <th class="p-4 text-center">Email</th>
                                        <th class="p-4 text-center">Updated At</th>
                                        <th class="p-4 text-center">Created At</th>
                                        <th class="p-4 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user, index) in users" :key="user.id" class="border-b">
                                        <td class="p-4 text-center">{{ index + 1 }}</td>
                                        <td class="p-4 font-bold text-center text-blue-700">{{ user.name }}</td>
                                        <td class="p-4 font-bold text-center text-blue-700">{{ user.email }}</td>
                                        <td class="p-4 font-bold text-center text-blue-700">{{ user.updated_at }}</td>
                                        <td class="p-4 font-bold text-center text-blue-700">{{ user.created_at }}</td>
                                        <td class="p-4 text-center">
                                            <PrimaryButton
                                            @click="editPermmisionName(user.id)">
                                                Edit
                                            </PrimaryButton>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>

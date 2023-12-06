<script setup>
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { notify } from "notiwind";
import AlertByNotify from '@/Components/AlertByNotify.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    permissions: {
        type: Array
    }
});

const savePermissionName = async (permission) => {
    try {
        const response = await axios.patch(route('admin.edit.permission', { id: permission.id }), {
            permission_name: permission.name
        });
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

const deletePermissionName = async (permission) => {
    const confirmMessage = "You won't be able to revert this!";

    Swal.fire({
        title: 'Are you sure?',
        text: confirmMessage,
        icon: 'warning',
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        showCancelButton: true,
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await axios.delete(route('admin.delete.permission.name', { id: permission.id }));
                if (response.status === 200) {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Permission Name has been deleted.",
                        icon: "success"
                    });
                    window.location.reload();
                }
                else if (response.status === 422) {
                    Swal.fire({
                        title: "Error",
                        text: "Cannot delete this Permission Name",
                        icon: "error"
                    });
                }
                else {
                    Swal.fire('Error', `Permission Name delete has error: ${response.status}`, 'error');
                }
            }
            catch (err) {
                Swal.fire('Error', 'An error occurred.', 'error');
            }
        }
    });
}
</script>

<template>
    <div>

        <Head title="List All Permissions" />
        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">List All Permissions</h2>
            </template>
            <AlertByNotify>

            </AlertByNotify>
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
                                        <td class="p-4 text-center">
                                            <TextInput id="permission_name" type="text" v-model="permission.name"
                                                @keyup.enter="savePermissionName(permission)"
                                                @blur="savePermissionName(permission)"
                                                class="w-full py-2 text-center border border-gray-300 rounded-md focus:outline-none focus:border-teal-500"
                                            />
                                        </td>
                                        <td class="text-center">
                                            <button @click="deletePermissionName(permission)"
                                                class="px-3 py-1 text-sm text-white transition-transform rounded-full shadow-md cursor-pointer bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 hover:shadow-2xl hover:scale-110 focus:outline-none focus:ring-2 focus:ring-pink-400">
                                                Delete Permission Name
                                            </button>
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

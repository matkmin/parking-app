<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { ref } from 'vue';
import axios from 'axios';
import AlertByNotify from '@/Components/AlertByNotify.vue';
import { notify } from "notiwind";
import Swal from 'sweetalert2';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

defineProps({
    roles: {
        type: Array
    },
    permissions: {
        type: Array
    }
});

const isChecked = (role, permission) => {

    const hasPermissions = role && role.permissions && Array.isArray(role.permissions);

    const hasPermission = hasPermissions && role.permissions.some(p => p.id === permission.id);

    return hasPermission;
};

const selectedRole = ref(null);

const selectedPermission = ref([]);

const newRoleName = ref('');

const showModal = ref(false);

const showPermission = (roleID) => {
    showModal.value = true;

    selectedRole.value = roleID;

};

const deleteRole = async (roleID) => {
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
                const response = await axios.delete(route('admin.delete.role', { roleID: roleID }));
                if (response.status === 200) {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your Role has been deleted.",
                        icon: "success"
                    });
                    window.location.reload();
                }
                else if (response.status === 422) {
                    Swal.fire({
                        title: "Error",
                        text: "Cannot delete role",
                        icon: "error"
                    });
                }
                else {
                    Swal.fire('Error', `Failed with status: ${response.status}`, 'error');
                }
            }
            catch (err) {
                Swal.fire('Error', 'An error occurred.', 'error');
            }
        }
    });
};

const savePermission = async () => {
    try {
        const response = await axios.post(route('admin.change.permission', { roleID: selectedRole.value }), {
            permissions: selectedPermission.value,
        });
        if (response.status === 200) {
            notify({
                group: "success",
                title: "Success",
                text: "Permission was changed successfully"
            }, 4000);
            showModal.value = false;
            window.location.reload();
        }
    } catch (error) {
        console.error('Error updating permissions:', error);
    }
};

const addNewRole = async () => {
    try {
        const response = await axios.post(route('admin.add.role'), {
            name: newRoleName.value,
        });

        if (response.status === 200) {
            notify({
                group: "success",
                title: "Success",
                text: "Role added successfully"
            }, 4000);
            newRoleName.value = '';
            window.location.reload();
        } else {
            notify({
                group: "error",
                title: "Error",
                text: "Failed to add role"
            }, 4000);
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            notify({
                group: "error",
                title: "Error",
                text: error.response.data.errors.name[0] || "An error occurred"
            }, 4000);
        } else {
            notify({
                group: "error",
                title: "Error",
                text: "An error occurred while adding the role"
            }, 4000);
        }
    }
};


</script>

<template>
    <div>

        <Head title="List Permissions" />
        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">List Permissions Role</h2>
            </template>
            <AlertByNotify></AlertByNotify>
            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="mb-2">
                        <div class="flex items-center px-2">
                            <InputLabel class="mr-2">
                                Press Enter to Save New Role:
                            </InputLabel>
                            <TextInput class="mr-2 text-sm" v-model="newRoleName" @keyup.enter="addNewRole"
                                placeholder="Enter new role name...">
                            </TextInput>
                        </div>
                    </div>
                    <div class="overflow-x-auto bg-white border rounded-md shadow-md">
                        <div class="p-6 text-gray-900">
                            <table class="min-w-full overflow-hidden">
                                <thead class="bg-gray-100 border-b">
                                    <tr>
                                        <th class="p-4 text-center">Roles</th>
                                        <th class="p-4 text-center">Permissions</th>
                                        <th class="p-4 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="role in roles" :key="role.id" class="border-b">
                                        <td class="p-4">
                                            <button
                                                class="px-2 py-1 mb-2 mr-2 text-sm font-bold text-white uppercase bg-yellow-600 rounded-md cursor-not-allowed">
                                                {{ role.name }}
                                            </button>
                                        </td>
                                        <td class="p-4">
                                            <ul class="flex flex-wrap p-2 list-none">
                                                <li v-for="permission in role.permissions" :key="permission.id"
                                                    class="flex items-center">
                                                    <div class="relative">
                                                        <button
                                                            class="px-2 py-1 mb-2 mr-2 text-sm text-white rounded-full cursor-not-allowed bg-lime-600">
                                                            {{ permission.name }}
                                                        </button>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center space-x-2">
                                                <PrimaryButton @click="showPermission(role.id)"
                                                    class="px-2 py-1 text-xs text-white transition-transform rounded-full shadow-md cursor-pointer bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 hover:shadow-2xl hover:scale-110 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                                    Assign
                                                </PrimaryButton>
                                                <PrimaryButton @click="deleteRole(role.id)"
                                                    class="px-2 py-1 text-xs text-white transition-transform rounded-full shadow-md cursor-pointer bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 hover:shadow-2xl hover:scale-110 focus:outline-none focus:ring-2 focus:ring-red-400">
                                                    Delete
                                                </PrimaryButton>
                                            </div>
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
                    <div v-for="role in roles" :key="role.id" v-show="role.id === selectedRole"
                        class="relative p-10 py-5 bg-white rounded-lg shadow-xl">
                        <h2 class="mb-4 font-bold text-blue-600 uppercase">
                            Assign Permission For {{ role.name }}
                        </h2>
                        <div v-for="permission in permissions" :key="permission.id" class="flex items-center mb-2">
                            <input type="checkbox" :id="permission.name" :name="permission.name" :value="permission.id"
                                :checked="isChecked(role, permission)" v-model="selectedPermission"
                                class="border-purple-300 rounded-md cursor-pointer focus:ring-2 focus:ring-purple-400" />
                            <InputLabel for="permission.name" class="ml-2 text-gray-700 ">{{ permission.name }}
                            </InputLabel>
                        </div>
                        <div class="text-right">
                            <PrimaryButton @click="savePermission()"
                                class="flex justify-center py-2 text-sm text-white transition-transform rounded-full shadow-md cursor-pointer bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 hover:shadow-2xl hover:scale-110 focus:outline-none focus:ring-2 focus:ring-teal-400">
                                Save New Permission
                            </PrimaryButton>
                        </div>
                        <button class="absolute text-gray-600 top-4 right-4 hover:text-gray-800" @click="showModal = false">
                            X
                        </button>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>


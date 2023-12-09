<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import TextInput from '@/Components/TextInput.vue';
import AlertByNotify from '@/Components/AlertByNotify.vue';
import { notify } from "notiwind";
import Swal from 'sweetalert2';
import { ref } from 'vue';

const { users, roles } = defineProps(['users', 'roles']);

const selectedRole = ref('');

const changeUserRole = async (userID) => {
    const user = users.find(u => u.id === userID);
    try {
        const response = await axios.post(route('admin.change.role', { id: userID }), {
            role: user.selectedRole,
        });

        if (response.status === 200) {
            notify({
                group: "success",
                title: "Success",
                text: "Role has change successfully"
            }, 4000);
            window.location.reload();
        } else {
            notify({
                group: "error",
                title: "Error",
                text: "Failed to change role"
            }, 4000);
        }
    } catch (error) {
        console.error('Error changing role:', error);
        notify({
            group: "error",
            title: "Error",
            text: "An error occurred while changing the role"
        }, 4000);
    }

}

const editUserName = async (user) => {
    try {
        const response = await axios.patch(route('admin.update.username', { id: user.id }), {
            name: user.name,
        });

        if (response.status === 200) {
            notify({
                group: "success",
                title: "Success",
                text: "User name has been successfully updated",
            }, 4000);
        } else {
            notify({
                group: "error",
                title: "Error",
                text: "Error updating user name",
            }, 4000);
        }
    } catch (error) {
        console.error('Error updating user name:', error);
        notify({
            group: "error",
            title: "Error",
            text: "Error updating user name",
        }, 4000);
    }
};


const editUserEmail = async (user) => {
    try {
        const response = await axios.patch(route('admin.update.useremail', { id: user.id }), {
            email: user.email,
        });

        if (response.status === 200) {
            notify({
                group: "success",
                title: "Success",
                text: "User email has been successfully updated",
            }, 4000);
        } else {
            notify({
                group: "error",
                title: "Error",
                text: "Error updating user email",
            }, 4000);
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            if (error.response.data.errors.email.includes('unique')) {
                notify({
                    group: "error",
                    title: "Error",
                    text: "This email address is already in use by another user",
                }, 4000);
            } else {
                notify({
                    group: "error",
                    title: "Error",
                    text: error.response.data.errors.email[0] || "An error occurred"
                }, 4000);
            }
        } else {
            notify({
                group: "error",
                title: "Error",
                text: "An error occurred while changing the email"
            }, 4000);
        }
    }
};


const deleteUser = async (userID) => {
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
                console.log(userID);
                const response = await axios.delete(route('admin.delete.user', { userID: userID }));
                console.log(response.data);
                if (response.status === 200) {
                    Swal.fire({
                        title: "Deleted!",
                        text: "User has been deleted.",
                        icon: "success"
                    });
                    window.location.reload();
                }
                else if (response.status === 422) {
                    Swal.fire({
                        title: "Error",
                        text: "Cannot delete this user",
                        icon: "error"
                    });
                }
                else {
                    Swal.fire('Error', `User delete has error: ${response.status}`, 'error');
                }
            }
            catch (err) {
                Swal.fire('Error', 'An error occurred.', 'error');
            }
        }
    });
};
</script>

<template>
    <div>

        <Head title="List User" />
        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">List Users</h2>
            </template>
            <AlertByNotify></AlertByNotify>
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
                                        <th class="p-4 text-center">Role</th>
                                        <th class="p-4 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user, index) in users" :key="user.id" class="border-b">
                                        <td class="p-4 text-center">{{ index + 1 }}</td>
                                        <td class="p-4 font-bold text-center text-blue-600">
                                            <div class="max-w-md mx-auto">
                                                <TextInput id="user.name" type="text" v-model="user.name"
                                                    @keyup.enter="editUserName(user)" @blur="editUserName(user)"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-teal-500" />
                                            </div>
                                        </td>
                                        <td class="p-4 font-bold text-center text-blue-600">
                                            <div class="max-w-md mx-auto">
                                                <TextInput id="user.email" type="text" v-model="user.email"
                                                    @keyup.enter="editUserEmail(user)" @blur="editUserEmail(user)"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-teal-500" />
                                            </div>
                                        </td>
                                        <td class="flex items-center p-4 text-center">
                                            <div class="mr-4 form-group">
                                                <select
                                                    class="text-center uppercase border border-red-300 rounded-md form-control custom-select"
                                                    @change="changeUserRole(user.id)" v-model="user.selectedRole">
                                                    <option disabled value="">Please select one</option>
                                                    <option v-for="role in roles" :key="role.id" :value="role.id">
                                                        {{ role.name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div v-for="role in user.roles" :key="role.id">
                                                <button
                                                    class="px-2 py-1 text-sm font-bold text-white uppercase rounded-md cursor-not-allowed bg-emerald-500">
                                                    {{ role.name }}
                                                </button>
                                            </div>
                                            <div class="ml-2 text-xs font-semibold text-gray-500">
                                                * Current Role
                                            </div>

                                        </td>

                                        <td class="p-4 text-center">
                                            <button @click="deleteUser(user.id)"
                                                class="px-3 py-1 text-sm text-white transition-transform rounded-full shadow-md cursor-pointer bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 hover:shadow-2xl hover:scale-110 focus:outline-none focus:ring-2 focus:ring-red-400">
                                                Delete User
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

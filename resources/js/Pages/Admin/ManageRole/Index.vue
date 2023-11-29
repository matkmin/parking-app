<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { ref } from 'vue';
import axios from 'axios';

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


// const checkedChange = (user, role, permission) => {
//     // Update the user's permissions based on the checkbox state
//     if (user && user.roles && Array.isArray(user.roles)) {
//         const targetRole = user.roles.find(userRole => userRole.id === role.id);

//         if (targetRole) {
//             if (targetRole.permissions.includes(permission.name)) {
//                 // If the permission is already in the array, remove it
//                 targetRole.permissions = targetRole.permissions.filter(p => p !== permission.name);
//             } else {
//                 // If the permission is not in the array, add it
//                 targetRole.permissions.push(permission.name);
//             }
//         }
//     }
// };

const selectedRole = ref(null);

const showModal = ref(false);

const showPermission = (roleID) => {
    showModal.value = true;

    selectedRole.value = roleID;

};

const savePermission = async () => {
    try {

        const response = await axios.post(route('admin.change.permission', { userID: user.value.id }), {
            permission: updatedPermissions
        });
    } catch (error) {
        console.error('Error updating permissions:', error);
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
            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
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
                                        <td class="p-4 text-base font-extrabold uppercase text-emerald-600">
                                            {{ role.name }}
                                        </td>
                                        <td class="p-4">
                                            <ul class="flex flex-wrap p-2 list-none">
                                                <li v-for="permission in role.permissions" :key="permission.id"
                                                    class="flex items-center">
                                                    <div class="relative">
                                                        <button
                                                            class="px-2 py-1 mb-2 mr-2 text-sm text-white rounded-full bg-amber-600">
                                                            {{ permission.name }}
                                                        </button>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                        <td class="p-4">
                                            <button @click="showPermission(role.id)"
                                                class="px-3 py-1 text-sm text-white bg-blue-500 rounded-full">Update</button>
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
                    <div v-for="role in roles"
                        :key="role.id"
                        v-show="role.id === selectedRole"
                        class="relative p-10 py-5 bg-white rounded-lg shadow-xl">
                        <h2 class="mb-4 font-bold text-blue-600 uppercase">
                            Update Permission For {{ role.name }}
                        </h2>
                        <div v-for="permission in permissions" :key="permission.id" class="flex items-center mb-2">
                            <input
                                type="checkbox"
                                :id="permission.name"
                                :name="permission.name"
                                :value="permission.name"
                                :checked="isChecked(role, permission)"
                                class="border-purple-300 rounded-md cursor-pointer focus:ring-2 focus:ring-purple-400" />
                            <InputLabel for="permission.name" class="ml-2 text-gray-700 ">{{ permission.name }}
                            </InputLabel>
                        </div>
                        <div class="text-right">
                            <PrimaryButton
                                @click="savePermission()"
                                class="flex justify-center py-2 text-sm text-white bg-teal-500 rounded-full cursor-pointer">
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


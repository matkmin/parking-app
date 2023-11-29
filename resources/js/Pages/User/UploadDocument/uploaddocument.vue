<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import Swal from 'sweetalert2';
import axios from 'axios';
import InputLabel from '@/Components/InputLabel.vue';
import { notify } from "notiwind";
import { NotificationGroup, Notification } from 'notiwind';

defineProps({
    documents: {
        type: Array
    }
});

const form = useForm({
    file: '',
    filename: '',

});

const uploadDocument = async () => {
    const fileInput = document.getElementById('file');
    const file = fileInput.files[0];

    if (file) {
        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await axios.post(route('user.upload.store'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });

            if (response.status === 200) {
                notify({
                    group: "success",
                    title: "Success",
                    text: "File uploaded successfully"
                }, 4000);
                window.location.reload();
            }
            else if (response.status === 422) {
                notify({
                    group: "error",
                    title: "Error",
                    text: "You can only upload one document"
                })

            }
            else {
                notify({
                    group: "error",
                    title: "Error",
                    text: "Error uploading file"
                }, 4000);
            }

        } catch (error) {
            notify({
                group: "error",
                title: "Error",
                text: "Error uploading file"
            }, 4000);
        }
    } else {
        notify({
            group: "error",
            title: "Error",
            text: "No file selected"
        }, 4000);
    }
};

const changeVerifyStatus = async (doc) => {
    const status = doc.status;

    const confirmMessage = status
        ? 'Are you sure you want to change this status as Not Verify?'
        : 'Are you sure you want to change this status as Verify?';

    Swal.fire({
        title: 'Confirmation',
        text: confirmMessage,
        icon: 'info',
        showCancelButton: true,
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {

                const response = await axios.post(route('admin.document.verify', { id: doc.id }), {
                    status: !status
                }
                );

                if (response.status === 200) {

                    doc.status = !doc.status;
                    Swal.fire('Success', 'The document status has been successfully changed.', 'success');
                }
                else if (response.status === 500) {

                    Swal.fire('Error', `Server connection error with status ${response.status}`, 'error');
                } else {

                    Swal.fire('Error', `Failed with status: ${response.status}`, 'error');
                }
            } catch (err) {

                Swal.fire('Error', 'An error occurred.', 'error');
                console.error("Error:", err);
            }
        }
    });
};

const deleteDocument = async (docID) => {

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
                const response = await axios.delete(route('user.document.delete', { id: docID }));

                if (response.status === 200) {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                    });
                    window.location.reload();
                }
                else if (response.status === 422) {
                    Swal.fire({
                        title: "Error",
                        text: "Cannot delete a verified document",
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

</script>

<template>
    <div>

        <Head title="Upload Documents" />
        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Upload Documents</h2>
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
                <div class="py-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <form v-if="can('user.create')" @submit.prevent="uploadDocument"
                        class="flex flex-col items-center justify-center w-full p-8 border-4 border-gray-300 border-dashed rounded dark:border-2">
                        <p class="mb-4 text-sm font-semibold md:text-base dark:text-red-400">
                            The maximum size for each file to be uploaded is 2 MB.
                            Only the following file types are acceptable: PDF, JPG, PNG.
                        </p>

                        <InputLabel for="file" class="relative cursor-pointer">
                            <button class="px-3 py-2 text-white bg-purple-500 rounded hover:bg-purple-600 focus:bg-purple-500">
                                Select a file from your device
                            </button>
                            <TextInput
                                id="file"
                                type="file"
                                v-model="form.file"
                                :disabled="form.processing"
                                @input="form.filename = $event.target.files[0].name"
                                accept=".pdf" class="sr-only"
                            />
                        </InputLabel>

                        <InputError :message="form.errors.file" />

                        <span class="mt-4 text-xs text-rose-800 md:text-sm">{{ form.filename }}</span>

                        <div class="mt-3">
                            <PrimaryButton
                            class="px-3 py-2 text-white bg-green-500 rounded hover:bg-green-600 focus:bg-green-500">
                                <span class="text-purple-500 dark:text-gray-100">
                                    {{ form.processing
                                        ? 'Uploading...'
                                        : 'Upload Document' }}
                                </span>
                            </PrimaryButton>
                        </div>
                    </form>

                    <div class="mt-8 overflow-x-auto">
                        <table class="min-w-full bg-white border border-collapse border-gray-300 rounded-lg shadow-md">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-gray-700 bg-gray-100">#</th>
                                    <th class="px-4 py-3 text-gray-700 bg-gray-100">Document Name</th>
                                    <th class="px-4 py-3 text-gray-700 bg-gray-100">Upload Date</th>
                                    <th class="px-4 py-3 text-gray-700 bg-gray-100">Upload By</th>
                                    <th class="px-4 py-3 text-gray-700 bg-gray-100">Status</th>
                                    <th class="px-4 py-3 text-gray-700 bg-gray-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-center bg-white divide-y divide-gray-200">
                                <tr v-for="(doc, index) in documents" :key="doc.id">
                                    <td class="px-4 py-3">{{ index + 1 }}</td>
                                    <td class="px-4 py-3">{{ doc.filename }}</td>
                                    <td class="px-4 py-3">{{ doc.created_at }}</td>
                                    <td class="px-4 py-3">{{ doc.user.email }}</td>
                                    <td class="px-4 py-3">
                                        <span v-if="doc.status" class="status verified">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" class="icon">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Verified
                                        </span>
                                        <span v-else class="status unverified">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" class="icon">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Unverified
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div
                                            class="flex flex-col items-center space-y-2 sm:items-start sm:space-y-0 sm:flex-row sm:space-x-4">
                                            <PrimaryButton v-if="can('admin.verify')" @click="changeVerifyStatus(doc)"
                                                :class="{
                                                    'px-3 py-2 text-white bg-red-500 rounded hover:bg-red-600': doc.status,
                                                    'px-3 py-2 text-white bg-green-500 rounded hover:bg-green-600': !doc.status
                                                }">
                                                {{ doc.status ? 'Unverify' : 'Verify' }}
                                            </PrimaryButton>

                                            <PrimaryButton :href="route('user.document.view', { filename: doc.filename })"
                                                class="px-3 py-2 text-white bg-yellow-500 rounded hover:bg-yellow-600 focus:bg-yellow-500">
                                                View
                                            </PrimaryButton>

                                            <PrimaryButton v-if="can('user.delete') && !doc.status"
                                                @click="deleteDocument(doc.id)"
                                                class="px-3 py-2 text-white bg-pink-500 rounded hover:bg-pink-600 focus:bg-pink-500"
                                                :disabled="doc.status">
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
        </AuthenticatedLayout>
    </div>
</template>

<style scoped>
.status {
    display: inline-flex;
    align-items: center;
    padding: 2px 6px;
    font-weight: bold;
    border-radius: 8px;
}

.verified {
    background-color: #e6f7ea;
    /* Adjust the background color as needed */
    color: #4caf50;
    /* Adjust the text color as needed */
}

.unverified {
    background-color: #fbe9e7;
    /* Adjust the background color as needed */
    color: #f44336;
    /* Adjust the text color as needed */
}

.icon {
    width: 16px;
    /* Adjust the width of the icon as needed */
    height: 16px;
    /* Adjust the height of the icon as needed */
    margin-right: 4px;
    /* Adjust the spacing between icon and text as needed */
}
</style>






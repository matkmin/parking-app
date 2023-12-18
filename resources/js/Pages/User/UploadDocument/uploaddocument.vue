<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import Swal from 'sweetalert2';
import axios from 'axios';
import InputLabel from '@/Components/InputLabel.vue';
import AlertByNotify from '@/Components/AlertByNotify.vue';
import { notify } from "notiwind";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    documents: {
        type: Object
    }
});

const form = useForm({
    file: '',
    filename: '',
    processing: false,

});

const uploadDocument = async () => {
    form.processing = true;

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
                    group: 'success',
                    title: 'Success',
                    text: 'File uploaded successfully',
                }, 4000);
                window.location.reload();
            } else if (error.response && error.response.status === 422) {
                notify({
                    group: "error",
                    title: "Error",
                    text: error.response.data.errors.file[0] || "An error occurred"
                }, 4000);
            } else {
                notify({
                    group: 'error',
                    title: 'Error',
                    text: 'Error uploading file',
                }, 4000);
            }
        } catch (error) {
            notify({
                group: 'error',
                title: 'Error',
                text: 'Error uploading file',
            }, 4000);
        } finally {
            form.processing = false;
        }
    } else {
        notify({
            group: 'error',
            title: 'Error',
            text: 'No file selected',
        }, 4000);
        form.processing = false;
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

const viewDocument = (docPath) => {

    window.open('/' + docPath, '_blank');
};
</script>

<template>
    <div>

        <Head title="Upload Documents" />
        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Upload Documents</h2>
            </template>
            <AlertByNotify></AlertByNotify>
            <div class="py-12">
                <div class="py-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <form v-if="can('user.create')" @submit.prevent="uploadDocument"
                        class="flex flex-col items-center justify-center w-full mb-5">
                        <p
                            class="p-8 mb-4 text-sm font-semibold border-4 border-gray-300 border-dashed rounded md:text-base dark:text-red-400 dark:border-2">
                            <span class="text-purple-500">Note:</span>
                            The maximum size for each file to be uploaded is <span class="text-green-500">2 MB
                            </span>.
                            Only the following file types are acceptable:
                            <span class="font-extrabold text-blue-500">PDF
                            </span>.
                        </p>

                        <InputLabel for="file"
                            class="relative flex items-center justify-center px-4 py-2 text-sm font-medium text-white transition-transform rounded-full shadow-md cursor-pointer bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 hover:shadow-2xl hover:scale-110 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"
                                    stroke="currentColor" class="inline-block w-5 h-5 mr-2">
                                    <path fill-rule="evenodd"
                                        d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z" />
                                </svg>
                                <span class="inline-block ">Select a file from your device</span>
                            </span>
                            <TextInput id="file" type="file" v-model="form.file" :disabled="form.processing"
                                @input="form.filename = $event.target.files[0].name" accept=".pdf" class="sr-only" />
                        </InputLabel>

                        <InputError :message="form.errors.file" />

                        <span class="mt-4 text-xs text-rose-800 md:text-sm">{{ form.filename }}</span>

                        <div class="mt-3">
                            <PrimaryButton :disabled="form.processing"
                                class="px-3 py-2 text-white transition-transform rounded-full shadow-md cursor-pointer bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 hover:shadow-2xl hover:scale-110 focus:outline-none focus:ring-2 focus:ring-green-400">
                                <span class="text-purple-500 dark:text-gray-100">
                                    {{ form.processing
                                        ? 'Uploading...'
                                        : 'Upload Document' }}
                                </span>
                            </PrimaryButton>
                        </div>
                    </form>
                    <div class="overflow-x-auto bg-white border rounded-md shadow-md">
                        <div class="p-6 text-gray-900">
                            <table class="min-w-full overflow-hidden" v-if="documents.data.length > 0">
                                <thead class="bg-gray-100 border-b">
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
                                    <tr v-for="(doc, index) in documents.data" :key="doc.id">
                                        <td class="px-4 py-3">{{ documents.from + index }}</td>
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
                                                        'px-3 py-2  text-white transition-transform rounded-full shadow-md cursor-pointer bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 hover:shadow-2xl hover:scale-110 focus:outline-none focus:ring-2 focus:ring-red-400': doc.status,
                                                        'px-3 py-2  text-white transition-transform rounded-full shadow-md cursor-pointer bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 hover:shadow-2xl hover:scale-110 focus:outline-none focus:ring-2 focus:ring-green-400': !doc.status
                                                    }">
                                                    {{ doc.status ? 'Unverify' : 'Verify' }}
                                                </PrimaryButton>

                                                <PrimaryButton @click="viewDocument(doc.file_path)"
                                                    class="px-3 py-2 text-white transition-transform rounded-full shadow-md cursor-pointer bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 hover:shadow-2xl hover:scale-110 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                                    View
                                                </PrimaryButton>

                                                <PrimaryButton v-if="can('user.delete') && !doc.status"
                                                    @click="deleteDocument(doc.id)"
                                                    class="px-3 py-2 text-white transition-transform rounded-full shadow-md cursor-pointer bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 hover:shadow-2xl hover:scale-110 focus:outline-none focus:ring-2 focus:ring-pink-400"
                                                    :disabled="doc.status">
                                                    Delete
                                                </PrimaryButton>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-else class="flex items-center justify-center h-full">
                                <div class="text-center text-gray-800 ">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="#FF0055" class="w-16 h-16 mx-auto mb-4 text-gray-500 animate-bounce">
                                        <path
                                            d="M13.879 10.414a2.501 2.501 0 0 0-3.465 3.465zm.707.707-3.465 3.465a2.501 2.501 0 0 0 3.465-3.465m-4.56-1.096a3.5 3.5 0 1 1 4.949 4.95 3.5 3.5 0 0 1-4.95-4.95ZM8 1c-1.573 0-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4s.875 1.755 1.904 2.223C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777C13.125 5.755 14 5.007 14 4s-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1" />
                                        <path
                                            d="M2 7v-.839c.457.432 1.004.751 1.49.972C4.722 7.693 6.318 8 8 8s3.278-.307 4.51-.867c.486-.22 1.033-.54 1.49-.972V7c0 .424-.155.802-.411 1.133a4.51 4.51 0 0 0-4.815 1.843A12.31 12.31 0 0 1 8 10c-1.573 0-3.022-.289-4.096-.777C2.875 8.755 2 8.007 2 7m6.257 3.998L8 11c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V10c0 1.007.875 1.755 1.904 2.223C4.978 12.711 6.427 13 8 13h.027a4.552 4.552 0 0 1 .23-2.002m-.002 3L8 14c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V13c0 1.007.875 1.755 1.904 2.223C4.978 15.711 6.427 16 8 16c.536 0 1.058-.034 1.555-.097a4.507 4.507 0 0 1-1.3-1.905" />
                                    </svg>
                                    <p class="mb-2 text-3xl font-semibold animate-bounce">Oops! No data available</p>
                                    <p class="text-sm text-gray-600 animate-bounce">
                                        It seems there is no data to display at the moment.
                                    </p>
                                </div>
                            </div>
                            <Pagination class="mt-6" :links="documents.links" />
                        </div>
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






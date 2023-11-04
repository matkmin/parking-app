<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Define a function to handle file uploads
const handleFileUpload = () => {
    // Implement your file upload logic here
    // You can use libraries like axios or fetch to send the selected file to your server.
};

</script>

<template>
    <div>

        <Head title="Upload Documents" />
        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Upload Documents</h2>
            </template>
            <div class="py-12">
                <div class="py-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <!-- File upload form -->
                    <form @submit.prevent="handleFileUpload" class="p-6 bg-white rounded-lg shadow-md">
                        <div class="mb-6">
                            <label for="file" class="text-gray-700">Select a file to upload</label>
                            <div class="flex items-center mt-2">
                                <label for="file"
                                    class="px-4 py-2 text-white bg-blue-500 rounded cursor-pointer hover:bg-blue-600">
                                    Browse
                                </label>
                                <input type="file" id="file" name="file" accept=".pdf, .doc, .docx" required
                                    class="sr-only">
                                <span class="ml-3" id="file-name">No file selected</span>
                            </div>
                        </div>
                        <div class="mb-6">
                            <button type="submit"
                                class="w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">
                                Upload
                            </button>
                        </div>
                    </form>
                    <div class="mt-8 overflow-x-auto">
                        <table class="min-w-full bg-white border border-collapse border-gray-300 rounded-lg shadow-md">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-gray-700 bg-gray-100">Document Name</th>
                                    <th class="px-4 py-3 text-gray-700 bg-gray-100">Upload Date</th>
                                    <th class="px-4 py-3 text-gray-700 bg-gray-100">Upload By</th>
                                    <th class="px-4 py-3 text-gray-700 bg-gray-100">Status</th>
                                    <th class="px-4 py-3 text-gray-700 bg-gray-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example rows (replace with your data) -->
                                <tr>
                                    <td class="px-4 py-3">Document Name 1</td>
                                    <td class="px-4 py-3">Document Name 1</td>
                                    <td class="px-4 py-3">Document Name 1</td>
                                    <td class="px-4 py-3">Not Verify</td>
                                    <td class="px-4 py-3 space-x-2">
                                        <button class="px-3 py-2 text-white bg-red-500 rounded hover:bg-red-600">
                                            Verify
                                        </button>
                                        <button class="px-3 py-2 text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                            Download
                                        </button>
                                        <button class="px-3 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                                            View
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3">Document Name 2</td>
                                    <td class="px-4 py-3">Document Name 2</td>
                                    <td class="px-4 py-3">Document Name 2</td>
                                    <td class="px-4 py-3">Not Verify</td>
                                    <td class="px-4 py-3 space-x-2">
                                        <button class="px-3 py-2 text-white bg-red-500 rounded hover:bg-red-600">
                                            Verify
                                        </button>
                                        <button class="px-3 py-2 text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                            Download
                                        </button>
                                        <button class="px-3 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                                            View
                                        </button>
                                    </td>
                                </tr>
                                <!-- Repeat as needed for each uploaded document -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>






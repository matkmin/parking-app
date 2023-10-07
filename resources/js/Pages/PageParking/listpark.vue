<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
const props = defineProps(['listParking']);
// console.log(listParking);
</script>

<template>
    <div>

        <Head title="Information Parking" />
        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Information Parking</h2>
            </template>
            <div class="mt-4">
                <div class="overflow-x-auto">
                    <table class="w-full overflow-hidden divide-y divide-gray-200 rounded-lg min-w-max whitespace-nowrap">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Reg No.:</th>
                                <th scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    In:</th>
                                <th scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Out:</th>
                                <th scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Duration:</th>
                                <th scope="col"
                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Amount to paid:</th>

                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="list in listParking" :key="listParking.id">
                                <td class="px-6 py-4">{{ list.vehicleRegNo }}</td>
                                <td class="px-6 py-4">{{ list.entryTime }}</td>
                                <td class="px-6 py-4">{{ list.exitTime }}</td>
                                <td class="px-6 py-4">{{ list.duration }}</td>
                                <td class="px-6 py-4">${{ list.amountPaid }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    vehicles: {
        type: Array,
    },
});
</script>

<template>
    <div>

        <Head title="List Vehicle" />
        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">List Vehicle</h2>
            </template>
            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="mb-4 text-right">
                        <Link
                            class="p-2 px-4 py-2 m-2 text-white transition-transform rounded-full shadow-md bg-gradient-to-r from-purple-500 to-purple-600 hover:shadow-lg hover:scale-105 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-300"
                            v-if="can('admin.create')" :href="route('admin.vehicles.create')">
                        <span>Add New Vehicle</span>
                        </Link>
                    </div>
                    <div class="overflow-x-auto">
                        <table
                            class="w-full overflow-hidden divide-y divide-gray-200 rounded-lg min-w-max whitespace-nowrap">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        #
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Vehicle Name
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Register Number
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Register State
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Register Fee
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Year
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Price
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Is Active
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-center bg-white divide-y divide-gray-200">
                                <tr v-for="vehicle in vehicles" :key="vehicle.vehicle.id">
                                    <td class="px-6 py-4">{{ vehicle.vehicle.id }}</td>
                                    <td class="px-6 py-4">{{ vehicle.vehicle.vehicle_type }}</td>
                                    <td class="px-6 py-4">{{ vehicle.reg_no }}</td>
                                    <td class="px-6 py-4">{{ vehicle.state_reg_no }}</td>
                                    <td class="px-6 py-4">RM {{ vehicle.register_fee }}</td>
                                    <td class="px-6 py-4">{{ vehicle.vehicle.year }}</td>
                                    <td class="px-6 py-4">RM {{ vehicle.vehicle.price }}</td>
                                    <td class="px-6 py-4">
                                        <span  :class="{ 'text-green-600': vehicle.is_active, 'text-red-600': !vehicle.is_active }">
                                            {{ vehicle.is_active ? 'Activated' : 'Deactivated' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <Link v-if="can('admin.update')" :href="route('admin.vehicles.edit', vehicle.id)"
                                            class="inline-flex items-center px-3 py-2 m-2 text-sm text-white transition-transform rounded-full shadow-md cursor-pointer btn btn-secondary bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 hover:shadow-2xl hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        <span>Edit</span>
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                            <!-- <div class="mt-4 text-center">
                                <div v-if="vehicles.last_page > 1">
                                    <inertia-link :href="vehicles.prev_page_url"
                                        class="px-3 py-1 text-sm border rounded-full">
                                        Previous
                                    </inertia-link>
                                    <template v-for="page in vehicles.last_page">
                                        <inertia-link :href="vehicles.url(page)"
                                            :class="{ 'bg-blue-600 text-white': page === vehicles.current_page }"
                                            class="px-3 py-1 text-sm border rounded-full">
                                            {{ page }}
                                        </inertia-link>
                                    </template>
                                    <inertia-link :href="vehicles.next_page_url"
                                        class="px-3 py-1 text-sm border rounded-full">
                                        Next
                                    </inertia-link>
                                </div>
                            </div> -->
                        </table>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>

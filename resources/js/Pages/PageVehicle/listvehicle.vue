<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';
import Swal from 'sweetalert2';

const showModal = ref(false);
const props = defineProps(['vehicle']);
const searchData = ref('');

const newVehicle = ref({
    vehicleBrand: '',
    vehicleName: '',
    vehiclePlateState: '',
    vehicleYearRelease: '',
    isAvailable: 1,
    price: 0,
});



const toggleAvailability = async (vehicle) => {
    const isAvailable = vehicle['Is Available'];
    const confirmMessage = isAvailable
        ? 'Are you sure you want to mark this vehicle as unavailable?'
        : 'Are you sure you want to mark this vehicle as available?';

    Swal.fire({
        title: 'Confirmation',
        text: confirmMessage,
        icon: 'info',
        showCancelButton: true,
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await axios.put(`api/vehicles/update-available/${vehicle.id}`, { isAvailable: !isAvailable });

                if (response.status === 200) {
                    vehicle['Is Available'] = !isAvailable;

                    Swal.fire('Success', 'Vehicle availability updated!', 'success');
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

const addVehicle = () => {
    showModal.value = true;
};

const saveVehicle = async () => {
    try {
        const response = await axios.post('api/vehicles/add-vehicle', newVehicle.value);
        console.log(response.status);
        if (response.status === 200) {
            showModal.value = false;
        } else if (response.status === 422) {

            console.error("Validation failed");
        } else if (response.status === 500) {

            console.error("Server error");
        } else {
            console.error("Failed with status: " + response.status);
        }
    } catch (error) {
        console.error("Error: ", error);
    }
};


const searchInfo = async () => {
    try {
        const response = await axios.get('api/vehicles/search-vehicle', {
            params: {
                data: searchData.value
            }
        });
        // console.log(response.status);
        if (response.status === 200) {
            // console.log("Yeah");
            vehicle.value = response.data;
            // console.log(vehicle.value);

        }
        else {
            console.error("Failed with status: " + response.status);
        }
    } catch (err) {
        console.error("Error", err)

    }
};

</script>


<template>
    <div>

        <Head title="Information Vehicle" />
        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Information Vehicle</h2>
            </template>
            <div class="py-12">
                <div class="py-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="mb-4 text-right">
                        <button
                            class="p-2 px-4 py-2 m-2 text-white transition-transform rounded-full shadow-md bg-gradient-to-r from-purple-500 to-purple-600 hover:shadow-lg hover:scale-105 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-300"
                            @click="addVehicle">Add Vehicle</button>
                    </div>
                    <div class="relative bg-white rounded-full shadow-md">
                        <input v-model="searchData" @input="searchInfo"
                            class="w-full px-4 py-2 text-gray-700 rounded-full focus:outline-none"
                            placeholder="Searching Data...." />
                        <div class="absolute inset-y-0 flex items-center pl-3 pointer-events-none left-3">
                            <i class="text-gray-400 fas fa-search"></i>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 mt-6 md:grid-cols-3 lg:grid-cols-4">
                        <div v-for="(vehicle, index) in vehicle" :key="index"
                            class="relative p-6 mx-auto transition-transform transform bg-white rounded-lg shadow-md cursor-pointer hover:scale-105 hover:shadow-lg"
                            @click="toggleAvailability(vehicle)">
                            <div class="text-2xl font-semibold text-gray-800">{{ vehicle['Vehicle Registration Number'] }}
                            </div>
                            <div class="mt-2 text-gray-600">
                                <div class="flex items-center">
                                    <span class="mr-2">Vehicle Brand:</span>
                                    <span>{{ vehicle['Vehicle Brand'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="mr-2">Vehicle Name:</span>
                                    <span>{{ vehicle['Vehicle Name'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="mr-2">Vehicle Plate State:</span>
                                    <span>{{ vehicle['Vehicle Plate State'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="mr-2">Vehicle Year Release:</span>
                                    <span>{{ vehicle['Vehicle Year Release'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="mr-2">Is Available:</span>
                                    <span
                                        :class="{ 'text-green-600': vehicle['Is Available'], 'text-red-600': !vehicle['Is Available'] }"
                                        @click.stop="toggleAvailability(vehicle)">
                                        {{ vehicle['Is Available'] ? 'Yes' : 'No' }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-4 text-2xl font-semibold"
                                :class="{ 'text-green-600': vehicle['Is Available'], 'text-red-600': !vehicle['Is Available'] }">
                                {{ vehicle['Amount'] }}
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center">
                        <div class="fixed inset-0 transition-opacity" @click="showModal = false">
                            <div class="absolute inset-0 bg-black opacity-50"></div>
                        </div>
                        <div class="relative p-10 py-5 bg-white rounded-lg shadow-xl">
                            <h2 class="mb-4 text-3xl font-semibold text-red-600">Add Vehicle</h2>

                            <div class="mb-4">
                                <label for="vehicle-brand" class="block text-sm font-medium text-gray-700">Vehicle
                                    Brand</label>
                                <input type="text" id="vehicle-brand" name="vehicle-brand"
                                    class="w-full p-3 mt-1 border-2 border-purple-300 rounded-md focus:ring-2 focus:ring-purple-400" />
                            </div>

                            <div class="mb-4">
                                <label for="vehicle-name" class="block text-sm font-medium text-gray-700">Vehicle
                                    Name</label>
                                <input type="text" id="vehicle-name" name="vehicle-name"
                                    class="w-full p-3 mt-1 border-2 border-purple-300 rounded-md focus:ring-2 focus:ring-purple-400" />
                            </div>

                            <div class="mb-4">
                                <label for="vehicle-plate-state" class="block text-sm font-medium text-gray-700">Vehicle
                                    Plate
                                    State</label>
                                <input type="text" id="vehicle-plate-state" name="vehicle-plate-state"
                                    class="w-full p-3 mt-1 border-2 border-purple-300 rounded-md focus:ring-2 focus:ring-purple-400" />
                            </div>

                            <div class="mb-4">
                                <label for="vehicle-year-release" class="block text-sm font-medium text-gray-700">Vehicle
                                    Year
                                    Release</label>
                                <input type="date" id="vehicle-year-release" name="vehicle-year-release"
                                    class="w-full p-3 mt-1 border-2 border-purple-300 rounded-md focus:ring-2 focus:ring-purple-400" />
                            </div>

                            <div class="mb-4">
                                <label for="is-available" class="block text-sm font-medium text-gray-700">Is
                                    Available</label>
                                <select id="is-available" name="is-available"
                                    class="w-full p-3 mt-1 border-2 border-purple-300 rounded-md focus:ring-2 focus:ring-purple-400">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                                <input type="number" id="price" name="price"
                                    class="w-full p-3 mt-1 border-2 border-purple-300 rounded-md focus:ring-2 focus:ring-purple-400" />
                            </div>

                            <div class="text-right">
                                <button
                                    class="p-3 px-6 py-3 m-2 text-white transition-transform rounded-full shadow-md cursor-pointer bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 hover:shadow-2xl hover:scale-110 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    @click="saveVehicle">Save</button>
                            </div>

                            <button class="absolute text-gray-600 top-4 right-4 hover:text-gray-800"
                                @click="showModal = false">X</button>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>

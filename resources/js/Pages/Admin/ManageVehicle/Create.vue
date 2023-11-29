<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import Checkbox from '@/Components/Checkbox.vue'

const form = useForm({
    vehicle_name: '',
    vehicle_type: '',
    year: '',
    price: '',
    is_available: ''
})

const submit = () => {
    form.post(route('admin.vehicles.store'))
}
</script>

<template>
    <Head title="Add New Vehicle" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Add New Vehicle</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 overflow-x-scroll text-gray-900">
                        <div class="p-6 overflow-x-scroll text-center text-gray-900">
                            <form @submit.prevent="submit" class="flex flex-col gap-4">
                                <div class="form-group">
                                    <InputLabel for="vehicle_name" value="Vehicle Name" />
                                    <TextInput id="vehicle_name" type="text" v-model="form.vehicle_name"
                                        :disabled="form.processing" />
                                    <InputError :message="form.errors.vehicle_name" />
                                </div>

                                <div class="form-group">
                                    <InputLabel for="vehicle_type" value="Vehicle Type" />
                                    <TextInput id="vehicle_type" type="text" v-model="form.vehicle_type"
                                        :disabled="form.processing" />
                                    <InputError :message="form.errors.vehicle_type" />
                                </div>

                                <div class="form-group">
                                    <InputLabel for="year" value="Year" />
                                    <TextInput id="year" type="date" v-model="form.year" :disabled="form.processing" />
                                    <InputError :message="form.errors.year" />
                                </div>

                                <div class="form-group">
                                    <InputLabel for="price" value="price" />
                                    <TextInput id="price" type="number" v-model="form.price" :disabled="form.processing" />
                                    <InputError :message="form.errors.price" />
                                </div>

                                <div class="form-group">
                                    <InputLabel for="is_available" value="Is Available" />
                                    <Checkbox id="is_available" v-model="form.is_available" :disabled="form.processing" />
                                    <InputError :message="form.errors.is_available" />
                                </div>

                                <div>
                                    <PrimaryButton :disabled="form.processing">Create New Vehicle</PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

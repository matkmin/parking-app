<script setup>
import { Link } from '@inertiajs/vue3';

const { links } = defineProps(['links']);
</script>
<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap mt-8">
            <template v-for="(link, key) in links" :key="key">
                <div v-if="link.url === null" class="px-4 py-3 mb-1 mr-1 text-sm leading-4 text-gray-400 border rounded"
                    v-html="link.label" />

                <Link v-else
                    class="px-4 py-3 mb-1 mr-1 text-sm leading-4 border rounded hover:bg-white focus:border-primary focus:text-primary"
                    :class="{ 'bg-white': link.active }" :href="link.url" v-html="link.label" />
            </template>
        </div>
    </div>
</template>



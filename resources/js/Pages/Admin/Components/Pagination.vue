<script setup>
import { ref, defineProps, onMounted } from 'vue';

const props = defineProps({
    links: Array,
});

</script>

<template>
    <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4" aria-label="Table navigation">
        <!-- <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
            Showing
            <span class="font-semibold text-gray-900 dark:text-white">1-10</span>
            of
            <span class="font-semibold text-gray-900 dark:text-white">1000</span>
        </span> -->
        <ul class="inline-flex items-stretch -space-x-px">
            <nav aria-label="Page navigation example">
                <ul class="flex items-center -space-x-px h-8 text-sm">
                    <li v-for="(link, index) in links" :key="index">
                        <a 
                            v-if="link.url === null" 
                            href="#" 
                            class="cursor-not-allowed opacity-50 flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700"
                            v-html="link.label" 
                        >
                        </a>
                        <a 
                            v-else 
                            :href="link.url" 
                            :class="link.active ? 'text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'" 
                            class="flex items-center justify-center px-3 h-8 leading-tight"
                            v-html="link.label"
                        >
                        </a>
                    </li>
                </ul>
            </nav>
        </ul>
    </nav>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';
import Search from '@/Pages/Admin/Components/Search.vue';
import Action from '@/Pages/Admin/Components/Action.vue';
import Filter from '@/Pages/Admin/Components/Filter.vue';
import Pagination from '@/Pages/Admin/Components/Pagination.vue';
import Main from "@/Pages/Admin/Components/Layout/Main.vue";
import { defineProps } from "vue";

defineProps({
    links: Array,
    canAdd: {
        type: Boolean,
        default: true
    },
    canFilter: {
        type: Boolean,
        default: true
    },
    filterOptions: Array,
    routeName: String
});


</script>

<template>
<Main>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <!-- Search -->
                    <Search />

                    <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <Link
                            v-if="canAdd"
                            :href="route(`${routeName}.add`)"
                            class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800"
                        >
                            <svg class="w-[16px] h-[16px] text-white mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                            </svg>
                            Add new
                        </Link>
                        <div class="flex items-center space-x-3 w-full md:w-auto">
                            <!-- Actions -->
                            <Action :routeName="routeName"/>

                            <!-- Filter -->
                            <Filter v-if="canFilter" :filterOptions="filterOptions" :routeName="routeName"/>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <slot name="tableHeader"/>
                        </thead>
                        <tbody>
                            <slot name="tableBody" />
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <Pagination :links="links"/>
            </div>
        </div>
    </section>
</Main>
</template>
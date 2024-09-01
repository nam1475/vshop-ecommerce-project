<script setup>
import Index from '@/Pages/Customer/Account/Index.vue'
import { defineProps, watch, ref } from 'vue'
import {router, Link} from '@inertiajs/vue3'
import { success, error } from '@/alert.js';
import Pagination from '@/Pages/Admin/Components/Pagination.vue'
// import { useRoute } from 'vue-router';

const props = defineProps({
    orders: Object
});

function cancelOrder(orderId){
    router.patch(route('customer.account.order.cancel', orderId), {}, {
        onSuccess: (page) => {
            success(page);
        },
        onError: (page) => {
            error(page);
        }
    });
}

const filterOptions = [
    {
        name: 'All orders',
        value: ''
    },
    {
        name: 'Success',
        value: 'success',
    },
    {
        name: 'Pending',
        value: 'pending'
    },
    {
        name: 'Cancelled',
        value: 'cancelled'
    },
];

const selectedFilter = ref(null);

watch(selectedFilter, () => {
    filterOrders(selectedFilter.value);
});

function filterOrders(value){
    router.get(route('customer.account.order.list'), 
    {
        ...(value != '' && { status: value }),
    },
    {
      preserveState: true,
      replace: true,
      preserveScroll: true
    }
    );
}

</script>

<template>
<Index>
    <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <div class="mx-auto max-w-5xl">
            <div class="gap-4 sm:flex sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">My orders</h2>
                
                <div class="mt-6 gap-4 space-y-4 sm:mt-0 sm:flex sm:items-center sm:justify-end sm:space-y-0">
                    <!-- Filter -->
                    <div>   
                        <label for="order-type" class="sr-only mb-2 block text-sm font-medium text-gray-900 dark:text-white">Select order type</label>
                        <select v-model="selectedFilter" id="order-type" class="block w-full min-w-[8rem] rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500">
                            <option v-for="option in filterOptions" :key="option.value" :value="option.value">
                                {{ option.name }}
                            </option>
                        </select>
                    </div>

                    <span class="inline-block text-gray-500 dark:text-gray-400"> from </span>

                    <div>
                        <label for="duration" class="sr-only mb-2 block text-sm font-medium text-gray-900 dark:text-white">Select duration</label>
                        <select id="duration" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500">
                        <option selected>this week</option>
                        <option value="this month">this month</option>
                        <option value="last 3 months">the last 3 months</option>
                        <option value="lats 6 months">the last 6 months</option>
                        <option value="this year">this year</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Orders -->
            <div class="mt-6 flow-root sm:mt-8">
                <div class="divide-y divide-gray-200 dark:divide-gray-700" v-if="orders.data.length">
                    <div class="flex flex-wrap items-center gap-y-4 py-6" v-for="order in orders.data" :key="order.id">
                        <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                            <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Order ID:</dt>
                            <dd class="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                                #{{ order.id }}
                            </dd>
                        </dl>

                        <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                        <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Date:</dt>
                        <dd class="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">{{ order.created_at }}</dd>
                        </dl>

                        <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                        <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Total:</dt>
                        <dd class="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">${{ order.total_price }}</dd>
                        </dl>

                        <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                            <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Status:</dt>
                            <dd v-if="order.status == 'success'" class="me-2 mt-1.5 inline-flex items-center rounded bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800 dark:bg-primary-900 dark:text-primary-300">
                                <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 4h-13m13 16h-13M8 20v-3.333a2 2 0 0 1 .4-1.2L10 12.6a1 1 0 0 0 0-1.2L8.4 8.533a2 2 0 0 1-.4-1.2V4h8v3.333a2 2 0 0 1-.4 1.2L13.957 11.4a1 1 0 0 0 0 1.2l1.643 2.867a2 2 0 0 1 .4 1.2V20H8Z" />
                                </svg>
                                {{ order.status }}
                            </dd>
                            <dd v-if="order.status == 'pending'" class="me-2 mt-1.5 inline-flex items-center rounded bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h6l2 4m-8-4v8m0-8V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v9h2m8 0H9m4 0h2m4 0h2v-4m0 0h-5m3.5 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm-10 0a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                                </svg>
                                {{ order.status }}
                            </dd>
                            <dd v-if="order.status == 'cancelled'" class="me-2 mt-1.5 inline-flex items-center rounded bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                                </svg>
                                Cancelled
                            </dd>
                        </dl>

                        <div class="w-full grid sm:grid-cols-2 lg:flex lg:w-64 lg:items-center lg:justify-end gap-4">
                            <button v-if="order.status == 'pending'" @click="cancelOrder(order.id)" type="button" class="w-full rounded-lg border border-red-700 px-3 py-2 text-center text-sm font-medium text-red-700 hover:bg-red-700 hover:text-white focus:outline-none focus:ring-4 focus:ring-red-300 dark:border-red-500 dark:text-red-500 dark:hover:bg-red-600 dark:hover:text-white dark:focus:ring-red-900 lg:w-auto">
                                Cancel order
                            </button>
                            <button v-if="order.status == 'success'" @click="orderAgain(order.id)" type="button" class="w-full rounded-lg bg-primary-700 px-3 py-2 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 lg:w-auto">
                                Order again
                            </button>
                            <Link :href="route('customer.account.order.details', order.id)" class="w-full inline-flex justify-center rounded-lg  border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 lg:w-auto">
                                View details
                            </Link>
                        </div>
                    </div>
                </div>

                <div v-else class="mt-6 flex w-full items-center justify-center rounded-lg bg-gray-200 px-5 py-2.5 text-sm font-medium text-black-800">
                    You don't have any order
                </div>

                <div class="flex justify-center">
                    <Pagination :links="orders.meta.links" v-if="orders.data.length"/>
                </div>
            </div>
            </div>
        </div>
    </section>
</Index>
</template>
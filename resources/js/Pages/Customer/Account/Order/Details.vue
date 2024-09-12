<script setup>
import OrderDetailLayout from '@/Layouts/OrderDetailLayout.vue';
import Index from '@/Pages/Customer/Account/Index.vue'
import { router } from '@inertiajs/vue3';
import { defineProps, reactive } from 'vue'

const props = defineProps({
    order: Object
});

const orderData = props.order.data;

const products = [];

orderData.order_items.forEach(item => {
    products.push(item.product);
});

function payOrder() {
    router.post(route('customer.order.store'), {
        carts: orderData.order_items,
        products: products,
        totalPrice: orderData.total_price,
        address: orderData.customer_address,
        sessionId: orderData.session_id
    });
}

</script>

<template>
<Index>
    <OrderDetailLayout :orderData="orderData" >
        <template #payOrderButton>
            <div class="gap-4 sm:flex sm:items-center">
                <button @click="payOrder" v-if="orderData.status == 'unpaid'" class="mt-4 flex w-full items-center justify-center rounded-lg bg-primary-700  px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300  dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 sm:mt-0">
                    Pay the order
                </button>
            </div>
        </template>

        <template #orderStatus>
            <ol class="relative ms-3 border-s border-gray-200">
                <!-- <li class="mb-10 ms-6">
                    <span class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 ring-8 ring-white dark:bg-gray-700 dark:ring-gray-800">
                        <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5"/>
                        </svg>
                    </span>
                    <h4 class="mb-0.5 text-base font-semibold text-gray-900 dark:text-white">Estimated delivery in 24 Nov 2023</h4>
                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Products delivered</p>
                </li>

                <li class="mb-10 ms-6">
                    <span class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 ring-8 ring-white dark:bg-gray-700 dark:ring-gray-800">
                        <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h6l2 4m-8-4v8m0-8V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v9h2m8 0H9m4 0h2m4 0h2v-4m0 0h-5m3.5 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm-10 0a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                        </svg>
                    </span>
                    <h4 class="mb-0.5 text-base font-semibold text-gray-900 dark:text-white">Today</h4>
                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Products being delivered</p>
                </li>
                
                <li class="mb-10 ms-6 text-primary-700 dark:text-primary-500">
                    <span class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 ring-8 ring-white dark:bg-primary-900 dark:ring-gray-800">
                        <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                        </svg>
                    </span>
                    <h4 class="mb-0.5 font-semibold">23 Nov 2023, 15:15</h4>
                    <p class="text-sm">Wait for confirmation</p>
                </li>

                <li class="mb-10 ms-6 text-primary-700 dark:text-primary-500">
                    <span class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 ring-8 ring-white dark:bg-primary-900 dark:ring-gray-800">
                        <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                        </svg>
                    </span>
                    <h4 class="mb-0.5 text-base font-semibold">{{ orderData.updated_at }}</h4>
                    <p class="text-sm">Payment accepted</p>
                </li>

                <li class="mb-10 ms-6 text-primary-700 dark:text-primary-500">
                    <span class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 ring-8 ring-white dark:bg-primary-900 dark:ring-gray-800">
                        <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                        </svg>
                    </span>
                    <h4 class="mb-0.5 font-semibold">19 Nov 2023, 10:47</h4>
                    <p class="text-sm">Pending payment</p>
                </li> -->

                <li class="ms-6 text-primary-700 dark:text-primary-500">
                    <span class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 ring-8 ring-white dark:bg-primary-900 dark:ring-gray-800">
                        <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                        </svg>
                    </span>                     
                    <div>
                        <h4 class="mb-0.5 font-semibold">{{ orderData.updated_at }}</h4>
                        <a href="#" class="text-sm font-medium hover:underline">{{ orderData.status }}</a>
                    </div>
                </li>
            </ol>
        </template>

    </OrderDetailLayout>
</Index>
</template>
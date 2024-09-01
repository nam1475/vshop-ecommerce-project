<script setup>
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

function formatNumber(number) {
    // Tách chuỗi thành các nhóm số bằng cách sử dụng regex
    return number.replace(/(\d{4})(\d{3})(\d{3})/, '$1 $2 $3');
}

function payOrder() {
    router.post(route('customer.order.store'), {
        carts: orderData.order_items,
        products: products,
        totalPrice: orderData.total_price,
        address: orderData.customer_address,
        sessionId: orderData.session_id
    });
}

function getTotalPrice(price, quantity){
    return price * quantity;
}

</script>

<template>
<Index>
    <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
    <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">View details of order #{{ orderData.id }}</h2>

        <div class="mt-6 sm:mt-8 lg:flex lg:gap-8">
            <div class="w-full divide-y divide-gray-200 overflow-hidden rounded-lg border border-gray-200 dark:divide-gray-700 dark:border-gray-700 lg:max-w-xl xl:max-w-2xl">
                <div class="space-y-4 p-6" v-for="item in orderData.order_items" :key="item.id">
                    <div class="flex items-center gap-6">
                        <a href="#" class="h-26 w-20 shrink-0">
                            <img class="h-full w-full dark:hidden" :src="item.product.images[0].url" alt="imac image" />
                        </a>

                        <a :href="route('customer.product.details', item.product.slug)" class="min-w-0 flex-1 font-medium text-gray-900 hover:underline dark:text-white">
                            {{ item.product.name }}
                        </a>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <p class="text-base font-normal text-gray-900 dark:text-white">x{{ item.quantity }}</p>
                        <p class="text-xl font-bold leading-tight text-gray-900 dark:text-white">${{ getTotalPrice(item.unit_price, item.quantity) }}</p>
                    </div>
                </div>

                <div class="space-y-4 bg-gray-50 p-6 dark:bg-gray-800">
                    <div class="space-y-2">
                        <dl class="flex items-center justify-between gap-4">
                            <dt class="font-normal text-gray-500 dark:text-gray-400">Original price</dt>
                            <dd class="font-medium text-gray-900 dark:text-white">${{ orderData.total_price }}</dd>
                        </dl>

                        <dl class="flex items-center justify-between gap-4">
                        <dt class="font-normal text-gray-500 dark:text-gray-400">Savings</dt>
                        <dd class="text-base font-medium text-green-500">-$299.00</dd>
                        </dl>

                        <dl class="flex items-center justify-between gap-4">
                        <dt class="font-normal text-gray-500 dark:text-gray-400">Store Pickup</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">$99</dd>
                        </dl>

                        <dl class="flex items-center justify-between gap-4">
                        <dt class="font-normal text-gray-500 dark:text-gray-400">Tax</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">$799</dd>
                        </dl>
                    </div>

                    <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                        <dt class="text-lg font-bold text-gray-900 dark:text-white">Total</dt>
                        <dd class="text-lg font-bold text-gray-900 dark:text-white">${{ orderData.total_price }}</dd>
                    </dl>
                </div>
            </div>

            <div class="mt-6 grow sm:mt-8 lg:mt-0">
                <div class="space-y-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Info</h3>

                    <div class="space-y-4 sm:space-y-2 rounded-lg border border-gray-100 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800 mb-6 md:mb-8">
                        <dl class="sm:flex items-center justify-between gap-4">
                            <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Date</dt>
                            <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ orderData.created_at }}</dd>
                        </dl>
                        <dl class="sm:flex items-center justify-between gap-4">
                            <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Name</dt>
                            <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ orderData.customer_address.customer.name }}</dd>
                        </dl>
                        <dl class="sm:flex items-center justify-between gap-4">
                            <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ orderData.customer_address.customer.email }}</dd>
                        </dl>
                        <dl class="sm:flex items-center justify-between gap-4">
                            <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Address</dt>
                            <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ orderData.customer_address.address1 }}</dd>
                        </dl>
                        <dl class="sm:flex items-center justify-between gap-4">
                            <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Phone</dt>
                            <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ formatNumber(orderData.customer_address.phone) }}</dd>
                        </dl>
                    </div>

                    <div class="gap-4 sm:flex sm:items-center">
                        <!-- <button type="button" class="w-full rounded-lg  border border-gray-200 bg-white px-5  py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">Cancel the order</button> -->

                        <a @click="payOrder" v-if="orderData.status == 'pending'" href="#" class="mt-4 flex w-full items-center justify-center rounded-lg bg-primary-700  px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300  dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 sm:mt-0">
                            Pay the order
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
</Index>
</template>
<script setup>
import { defineProps } from 'vue';
import Main from '@/Pages/Admin/Components/Layout/Main.vue';

defineProps({
    customer: Object,
    customerAddresses: Array
});

</script>

<template>
<Main>
    <section class="bg-white dark:bg-gray-900">
        <div class="py-6 px-4 mx-auto max-w-2xl">
            <h2 class="mb-2 text-xl font-semibold leading-none text-gray-900 md:text-2xl dark:text-white">Customer Information</h2>
            <dl>
                <!-- <dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Details</dt> -->
                <dd class="mb-4 font-light text-gray-500 sm:mb-5">Name: {{ customer.name }}</dd>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5">Email: {{ customer.email }}</dd>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5">Phone: {{ customer.phone }}</dd>
            </dl>
        </div>
        <div class="py-6 px-4 mx-auto max-w-2xl">
            <h2 class="mb-2 text-xl font-semibold leading-none text-gray-900 md:text-2xl dark:text-white">Customer Addresses</h2>
            <dl>
                <dd class="mb-4 font-light text-gray-500 sm:mb-5" v-for="address in customerAddresses" :key="address.id">
                    {{ address.address }}, {{ address.ward }}, {{ address.district }}, {{ address.province }}
                    <span v-if="address.is_main" class="ml-2 text-sm text-gray-700 bg-gray-200 px-2 py-1 rounded-full">
                        Default
                    </span>
                </dd>
            </dl>
        </div>
    </section>
</Main>
</template>
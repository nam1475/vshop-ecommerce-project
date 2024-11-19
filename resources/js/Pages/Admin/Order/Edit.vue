<script setup>
import Main from '@/Pages/Admin/Components/Layout/Main.vue'
import { router } from '@inertiajs/vue3';
import { defineProps, reactive, ref } from 'vue'
import { success, error } from '@/alert.js';
import OrderDetailLayout from '@/Layouts/OrderDetailLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    order: Object,
    title: String
});

const orderData = props.order.data;

const statusOptions = [
    {
        name: 'Unpaid',
        value: 'unpaid'
    },
    {
        name: 'Paided',
        value: 'paided'
    },
    {
        name: 'Pending confirmation',
        value: 'pending confirmation'
    },
    {
        name: 'Confirmed',
        value: 'confirmed'
    },
    {
        name: 'Delivering',
        value: 'delivering'
    },
    {
        name: 'Delivered',
        value: 'delivered'
    },
    {
        name: 'Cancelled',
        value: 'cancelled'
    }   
];

const checkedStatus = ref(orderData.status);

function updateStatus(orderId) {
    router.patch(route('admin.order.update', orderId), 
    {
        status: checkedStatus.value
    }, 
    {
        onSuccess: (page) => {
            success(page);
        },
        onError: (page) => {
            error(page);
        }
    });
}
</script>

<template>
<Head :title="title" />
<Main>
    <OrderDetailLayout :orderData="orderData">
        <template #orderStatus>
            <form @submit.prevent="updateStatus(orderData.id)">
                <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li v-for="status in statusOptions" :key="status.value" class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input :id="status.value" type="radio" :value="status.value" v-model="checkedStatus" name="list-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label :for="status.value" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ status.name }}</label>
                        </div>
                    </li>
                </ul>

                <div class="mt-4 gap-4 sm:flex sm:items-center">
                    <button type="submit" class="mt-4 flex w-full items-center justify-center rounded-lg bg-primary-700  px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300  dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 sm:mt-0">
                        Submit
                    </button>
                </div>
            </form>
        </template>
    </OrderDetailLayout>
</Main>
</template>
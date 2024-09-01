<script setup>
import {defineProps, reactive} from 'vue'
import Index from '@/Pages/Customer/Account/Index.vue'
import { usePage, useForm, Link, router } from '@inertiajs/vue3'
import Modal from '@/Pages/Customer/Components/Modal.vue';
import { success, error, warning } from '@/alert';

const props = defineProps({
    customerAddresses: Array
});

const customer = usePage().props.auth.customer;

// const formEditAddress = reactive(props.customerAddresses);

const arr = reactive({
    is_main: 0
});

function findAddressById(addressId){
    return props.customerAddresses.find((address) => address.id == addressId);
    // return formEditAddress.find((address) => address.id == addressId);
}

const formAddAddress = useForm({
    address1: '',
    city: '',
    state: '',
    is_main: 0,
    customer_id: customer.id
});

function updateAddress(addressId){
    const address = findAddressById(addressId);
    router.patch(route('customer.account.address.update', addressId), address, {
        onSuccess: (page) => {
            success(page);  
        },
        onError: (page) => {
            error(page);
        },
    });
}

function deleteAddress(addressId){
    warning()
    .then((result) => {
        if (result.isConfirmed) {
            router.delete(route('customer.account.address.delete', addressId), {}, {
                onSuccess: (page) => {
                    success(page);
                },
                onError: (page) => {
                    error(page);
                },
            });
        }
    })
}

function addAddress(){
    formAddAddress.post(route('customer.account.address.store'), {
        onSuccess: (page) => {
            success(page);
            formAddAddress.reset();
        },
        onError: (page) => {
            error(page);
        },
    });
}

</script>

<template>
<Index>
    <div class="max-w-4xl mx-auto p-6 bg-gray-100 rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">My address</h1>

            <Modal title="Add new address" id="modal-add-address" buttonText="Add new address">
                <form action="" @submit.prevent="addAddress()">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 px-4 py-6">
                        <div class="col-span-1">
                            <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Address
                            </label>
                            <input v-model="formAddAddress.address1" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
                        </div>

                        <div class="col-span-1">
                            <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                City
                            </label>
                            <input v-model="formAddAddress.city" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
                        </div>

                        <div class="col-span-1">
                            <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                State
                            </label>
                            <input v-model="formAddAddress.state" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
                        </div>

                        <div class="col-span-2">    
                            <input :id="`is-main-${formAddAddress.id}`" type="checkbox" :checked="formAddAddress.is_main" v-model="formAddAddress.is_main" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label :for="`is-main-${formAddAddress.id}`" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Set as default</label>
                        </div>
                        
                    </div>

                    <!-- Modal footer -->
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit" data-modal-hide="modal-add-address" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Submit
                        </button>
                        <button data-modal-hide="modal-add-address" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            Close
                        </button>
                    </div>
                    
                </form>
            </Modal>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow mb-6" v-for="ca in customerAddresses" :key="ca.id">
        <!-- <div class="bg-white p-6 rounded-lg shadow mb-6" v-for="ca in formEditAddress" :key="ca.id"> -->
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold">{{ customer.name }} 
                        <span v-if="ca.is_main" class="ml-2 text-sm text-gray-600 bg-gray-200 px-2 py-1 rounded-full">
                            Default
                        </span>
                    </h3>
                    <p class="text-gray-700">{{ customer.phone }}</p>
                    <p class="text-gray-700">{{ ca.address1 }}, {{ ca.city }}, {{ ca.state }}</p>
                </div>

                <div class="flex space-x-4">
                    <Modal title="Edit address" :id="`modal-edit-address-${ca.id}`" buttonText="Update">
                        <form action="" @submit.prevent="updateAddress(ca.id)">
                            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 px-4 py-6">
                                <div class="col-span-1">
                                    <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Address
                                    </label>
                                    <input v-model="ca.address1" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
                                </div>

                                <div class="col-span-1">
                                    <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        City
                                    </label>
                                    <input v-model="ca.city" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
                                </div>

                                <div class="col-span-1">
                                    <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        State
                                    </label>
                                    <input v-model="ca.state" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
                                </div>

                                <div class="col-span-2">    
                                    <input :id="`is-main-${ca.id}`" type="checkbox" true-value="1" false-value="0" v-model="ca.is_main" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label :for="`is-main-${ca.id}`" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Set as default</label>
                                </div>
                                
                            </div>

                            <!-- Modal footer -->
                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button type="submit" :data-modal-hide="`modal-edit-address-${ca.id}`" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Submit
                                </button>
                                <button :data-modal-hide="`modal-edit-address-${ca.id}`" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                    Close
                                </button>
                            </div>
                            
                        </form>
                    </Modal>

                    <button type="button" @click="deleteAddress(ca.id)" class="block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Delete</button>
                </div>
            </div>
        </div>
    </div>
</Index>
</template>
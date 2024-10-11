<script setup>
import { Link, usePage, router, useForm } from '@inertiajs/vue3';
import Main from '@/Pages/Customer/Components/Layout/Main.vue';
import { watch, computed, reactive, defineProps } from 'vue';
import { success, error } from '@/alert.js';

const props = defineProps({
  customerMainAddress: Object,
});

const cart = computed(() => usePage().props.cart.data);
const cartItems = computed(() => cart.value.items);
const products = computed(() => cart.value.products);
const cartTotal = computed(() => cart.value.total || 0);
const customer = computed(() => usePage().props.auth.customer || null);

function getTotalPrice(price, productId){
  return price * getQuantity(productId);
}

function getCartItem(productId){
  return cartItems.value.find(item => 
    item.product_id == productId
  );
}

function getQuantity(productId){
  const cartItem = getCartItem(productId);
  return cartItem.quantity;
}

function updateCartItem(productId, quantity){
  const cartItem = getCartItem(productId);
  router.patch(route('customer.cart.update', cartItem.id), 
  {
    quantity: quantity,
  },
  {
    onSuccess: (page) => {
      success(page);
    },
    onError: (page) => {
      error(page);
    }
  }
  );
}

function removeCartItem(productId){
  const cartItem = getCartItem(productId);
  router.delete(route('customer.cart.destroy', cartItem.id),
  {
    onSuccess: (page) => {
      success(page);
    },
    onError: (page) => {
      error(page);
    }
  });
}

const formAddress = reactive({
  address: '',
  city: '',
  state: '',
  phone: '',
  zip_code: '',
  country_code: '',
  type: '',
});

if(props.customerMainAddress){
  // formAddress = props.customerMainAddress;
  Object.assign(formAddress, props.customerMainAddress);
}

// const form = props.customerMainAddress ? useForm(formAddress) : useForm({});
// const formAddress = computed(() => props.customerMainAddress);

function checkout(){
  router.post(route('customer.order.store'), {
    carts: cartItems.value,
    products: products.value,
    totalPrice: cartTotal.value,
    address: formAddress,
  });
}

</script>

<template>
<Main>
<section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
  <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Shopping Cart</h2>

    <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
      <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl">
        <div class="space-y-6" v-if="products.length">
          <div 
            class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6"
            v-for="product in products"
            :key="product.id" 
          >
            <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
              <a href="#" class="shrink-0 md:order-1">
                <img class="h-26 w-20 dark:hidden" :src="product.images[0]" alt="imac image" />
              </a>

              <label for="counter-input" class="sr-only">Choose quantity:</label>
              <div class="flex items-center justify-between md:order-3 md:justify-end">
                <div class="flex items-center">
                  <button 
                    type="button" 
                    id="decrement-button" 
                    @click.prevent="updateCartItem(product.id, getQuantity(product.id) - 1)" 
                    data-input-counter-decrement="counter-input" 
                    class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700"
                    :class="getQuantity(product.id) == 1 ? 'cursor-not-allowed opacity-50' : 'cursor-pointer'"
                    :disabled="getQuantity(product.id) == 1" 
                  >
                    <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                    </svg>
                  </button>
                  
                  <input type="text" id="counter-input" data-input-counter class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0 dark:text-white" placeholder="" 
                    :value="getQuantity(product.id)" 
                    required
                  />
                  
                  <button type="button" id="increment-button" @click.prevent="updateCartItem(product.id, getQuantity(product.id) + 1)" data-input-counter-increment="counter-input" class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                    <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                    </svg>
                  </button>
                </div>
                <div class="text-end md:order-4 md:w-32">
                  <p class="text-base font-bold text-gray-900 dark:text-white">${{ getTotalPrice(product.price, product.id) }}</p>
                </div>
              </div>

              <div class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md">
                <Link :href="route('customer.product.details', product.slug)" class="text-base font-medium text-gray-900 hover:underline dark:text-white">
                  {{ product.name }}
                </Link>

                <div class="flex items-center gap-4">
                  <button @click="removeCartItem(product.id)" type="button" class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500">
                    <svg class="me-1.5 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                    </svg>
                    Remove
                  </button>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div v-else class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
          <p class="text-lg text-gray-500 text-center">
            Cart is empty
          </p>
        </div>

        <div class="hidden xl:mt-8 xl:block">
            <div class="max-w-full py-8">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Shipping Address</h2>
                <!-- <form action="#"> -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-1">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" v-model="formAddress.name" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Phone" required="">
                        </div>
                        <div class="col-span-1">
                            <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone</label>
                            <input type="text" v-model="formAddress.phone" name="phone" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Phone" required="">
                        </div>

                        <div class="col-span-1">
                            <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                            <input type="text" v-model="formAddress.address" name="address" id="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Address" required="">
                        </div>
                        <div class="col-span-1">
                            <label for="province" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Province</label>
                            <input type="text" v-model="formAddress.province" name="province" id="province" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="City" required="">
                        </div>
                        <div class="col-span-1">
                            <label for="district" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">District</label>
                            <input type="text" v-model="formAddress.district" name="district" id="district" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="State" required="">
                        </div>
                        <div class="col-span-1">
                            <label for="ward" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ward</label>
                            <input type="text" v-model="formAddress.ward" name="ward" id="ward" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="State" required="">
                        </div>
                        <div class="col-span-1">
                            <label for="zip-code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Zip Code</label>
                            <input type="number" v-model="formAddress.zip_code" name="zipCode" id="zip-code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Zip Code" required="">
                        </div>
                        <div class="col-span-1">
                            <label for="country-code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Country Code</label>
                            <input type="number" v-model="formAddress.country_code" name="countryCode" id="country-code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Country Code" required="">
                        </div>
                          
                    </div>
                    <!-- <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                        Submit
                    </button> -->
                <!-- </form> -->
            </div>
        </div>
      </div>

      <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full">
        <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
          <p class="text-xl font-semibold text-gray-900 dark:text-white">Order summary</p>

          <div class="space-y-4">
            <div class="space-y-2">
              <dl class="flex items-center justify-between gap-4">
                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Original price</dt>
                <dd class="text-base font-medium text-gray-900 dark:text-white">$7,592.00</dd>
              </dl>

              <dl class="flex items-center justify-between gap-4">
                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Savings</dt>
                <dd class="text-base font-medium text-green-600">-$299.00</dd>
              </dl>

              <dl class="flex items-center justify-between gap-4">
                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Store Pickup</dt>
                <dd class="text-base font-medium text-gray-900 dark:text-white">$99</dd>
              </dl>

              <dl class="flex items-center justify-between gap-4">
                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Tax</dt>
                <dd class="text-base font-medium text-gray-900 dark:text-white">$799</dd>
              </dl>
            </div>

            <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
              <dt class="text-base font-bold text-gray-900 dark:text-white">Total</dt>
              <dd class="text-base font-bold text-gray-900 dark:text-white">${{ cartTotal }}</dd>
            </dl>
          </div>

          <form @submit.prevent="checkout">
            <button type="submit" :disabled="!cart.count" :class="{ 'cursor-not-allowed': !cart.count }" class="flex w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
              Proceed to Checkout
            </button>
          </form>
          
          <div class="flex items-center justify-center gap-2">
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400"> or </span>
            <Link :href="route('home')" title="" class="inline-flex items-center gap-2 text-sm font-medium text-primary-700 underline hover:no-underline dark:text-primary-500">
              Continue Shopping
              <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
              </svg>
            </Link>
          </div>
        </div>

        <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
          <form class="space-y-4">
            <div>
              <label for="voucher" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Do you have a voucher or gift card? </label>
              <input type="text" id="voucher" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="" required />
            </div>
            <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Apply Code</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
</Main>
</template>
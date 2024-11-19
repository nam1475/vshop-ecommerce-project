<script setup>
import {
  Dialog,
  DialogPanel,
  Popover,
  PopoverButton,
  PopoverGroup,
  PopoverPanel,
  Tab,
  TabGroup,
  TabList,
  TabPanel,
  TabPanels,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'
import { Bars3Icon, MagnifyingGlassIcon, ShoppingBagIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue';

const auth = computed(() => usePage().props.auth);
const cart = computed(() => usePage().props.cart);    
const categories = computed(() => usePage().props.categories);

const open = ref(false);
</script>

<template>
  <div class="bg-white sticky top-0 z-50">
    <header class="relative bg-white">
      <nav aria-label="Top" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="border-b border-gray-200">
          <div class="flex h-16 items-center">
            <!-- Logo -->
            <div class="ml-4 flex lg:ml-0">
              <Link :href="route('home')">
                <span class="sr-only">Your Company</span>
                <ApplicationLogo />
              </Link>
            </div>

            <!-- Flyout menus -->
            <PopoverGroup class="hidden lg:ml-8 lg:block lg:self-stretch">
              <div class="flex h-full space-x-8">
                <Popover v-for="category in categories" :key="category.id" class="flex" v-slot="{ open }">
                  <!-- Main category -->
                  <div class="relative flex">
                    <PopoverButton :class="[open ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-700 hover:text-gray-800', 'relative z-10 -mb-px flex items-center border-b-2 pt-px text-sm font-medium transition-colors duration-200 ease-out']">
                      {{ category.name }}
                    </PopoverButton>
                  </div>

                  <!-- Sub category -->
                  <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
                    <PopoverPanel class="absolute z-40 inset-x-0 top-full text-sm text-gray-500">
                      <!-- Presentational element used to render the bottom shadow, if we put the shadow on the actual panel it pokes out the top, so we use this shorter element to hide the top of the shadow -->
                      <div class="absolute inset-0 top-1/2 bg-white shadow" aria-hidden="true" />
                      <div class="relative bg-white">
                        <div class="mx-auto max-w-7xl px-8">
                          <div class="text-center text-3xl font-bold tracking-tight text-gray-900 pt-4">
                            <Link :href="route('customer.category.index', category.slug)" class="hover:underline">{{ category.name }}</Link>
                          </div>

                          <div class="grid grid-cols-2 gap-x-8 gap-y-10 py-10">
                            <div class="col-start-2 grid grid-cols-2 gap-x-8">
                              <div v-for="item in category.children_recursive.slice(0, 2)" :key="item.id" class="group relative text-base sm:text-sm">
                                <div class="aspect-h-1 aspect-w-1 overflow-hidden rounded-lg bg-gray-100 group-hover:opacity-75">
                                  <img :src="item.images[0]" class="object-cover object-center" />
                                </div>
                                <a :href="route('customer.category.index', item.slug)" class="mt-6 block font-medium text-gray-900">
                                  <span class="absolute inset-0 z-10" aria-hidden="true" />
                                  {{ item.name }}
                                </a>
                                <p aria-hidden="true" class="mt-1">Shop now</p>
                              </div>
                            </div>

                            <div class="row-start-1 grid grid-cols-3 gap-x-8 gap-y-10 text-sm">
                              <div v-for="child in category.children_recursive" :key="child.id">
                                <Link :href="route('customer.category.index', child.slug)" :id="`${child.name}-heading`" class="hover:underline font-medium text-base font-black text-gray-900">
                                  {{ child.name }}
                                </Link>
                        
                                <ul role="list" :aria-labelledby="`${child.name}-heading`" class="mt-6 space-y-6 sm:mt-4 sm:space-y-4">
                                  <li v-for="item in child.children_recursive" :key="item.name" class="flex">
                                    <Link :href="route('customer.category.index', item.slug)" class="hover:underline">{{ item.name }}</Link>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </PopoverPanel>
                  </transition>
                </Popover>

                <!-- <a v-for="page in navigation.pages" :key="page.name" :href="page.href" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-800">{{ page.name }}</a> -->
              </div>
            </PopoverGroup>
            
            <!-- Cart and Account -->
            <div class="ml-auto flex items-center" v-if="auth.customer && auth.customer.email_verified_at">
              <Link :href="route('customer.cart.index')" id="myCartDropdownButton1" type="button" class="relative inline-flex items-center rounded-lg justify-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm font-medium leading-none text-gray-900 dark:text-white">
                <span class="sr-only">
                  Cart
                </span>
                <svg class="w-5 h-5 lg:me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312"/>
                </svg> 
                <span class="hidden sm:flex">My Cart</span>
                <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -end-2 dark:border-gray-900">
                  {{ cart.data.count }}  
                </div>              
              </Link>

              <button id="userDropdownButton1" data-dropdown-toggle="userDropdown1" type="button" class="inline-flex items-center rounded-lg justify-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm font-medium leading-none text-gray-900 dark:text-white">
                <svg class="w-5 h-5 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>              
                Account
                <svg class="w-4 h-4 text-gray-900 dark:text-white ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                </svg> 
              </button>

              <div id="userDropdown1" class="hidden z-10 w-56 divide-y divide-gray-100 overflow-hidden overflow-y-auto rounded-lg bg-white antialiased shadow dark:divide-gray-600 dark:bg-gray-700">
                <ul class="p-2 text-start text-sm font-medium text-gray-900 dark:text-white">
                  <li><Link :href="route('customer.account.info')" title="" class="inline-flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600"> My Account </Link></li>
                </ul>
            
                <div class="p-2 text-sm font-medium text-gray-900 dark:text-white">
                  <Link :href="route('logout')" method="post" title="" class="inline-flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600"> 
                    Sign Out 
                  </Link>
                </div>
              </div>
            </div>

            <div class="ml-auto flex items-center" v-else>
              <button id="userDropdownButton2" data-dropdown-toggle="userDropdown2" type="button" class="inline-flex items-center rounded-lg justify-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm font-medium leading-none text-gray-900 dark:text-white">
                <svg class="w-5 h-5 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>              
                <svg class="w-4 h-4 text-gray-900 dark:text-white ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                </svg> 
              </button>

              <div id="userDropdown2" class="hidden z-10 w-56 divide-y divide-gray-100 overflow-hidden overflow-y-auto rounded-lg bg-white antialiased shadow dark:divide-gray-600 dark:bg-gray-700">
                <ul class="p-2 text-start text-sm font-medium text-gray-900 dark:text-white">
                  <li><Link :href="route('login')" class="inline-flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600"> Login </Link></li>
                </ul>
            
                <div class="p-2 text-sm font-medium text-gray-900 dark:text-white">
                  <Link :href="route('register')" class="inline-flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600"> 
                    Register 
                  </Link>
                </div>
              </div>
            </div>

          </div>
        </div>
      </nav>
    </header>
  </div>
</template>




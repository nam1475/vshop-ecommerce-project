<script setup>
import { Link } from '@inertiajs/vue3';

const modules = [
  { 
    name: 'Dashboard', 
    route: 'admin.dashboard.index',
    // dropDown: false
  },
  // { 
  //   name: 'Pages', 
  //   subModules: [
      
  //   ],
  //   dropDown: true
  // },
  {
    name: 'Categories',
    route: 'admin.category.index'
  },
  {
    name: 'Brands',
    route: 'admin.brand.index'
  },
  {
    name: 'Products',
    route: 'admin.product.index'
  },
  {
    name: 'Orders',
    route: 'admin.order.index'
  },
  {
    name: 'Customers',
    route: 'admin.customer.index'
  },
  {
    name: 'Roles',
    route: 'admin.role.index'
  },
  {
    name: 'Permissions',
    route: 'admin.permission.index'
  },
  {
    name: 'Users',
    route: 'admin.user.index'
  },
  {
    name: 'Profile',
    route: 'admin.profile.index'
  },
];
</script>

<template>
    <aside
      class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform -translate-x-full bg-white border-r border-gray-200 md:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
      aria-label="Sidenav"
      id="drawer-navigation"
    >
      <div class="overflow-y-auto py-5 px-3 h-full bg-white dark:bg-gray-800">
        <ul class="space-y-2">
          <li v-for="module in modules" :key="module.name">
            <!-- <Link
              v-if="!module.dropDown"
              :href="route(module.route)"
              class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
            > -->
            <Link
              :href="route(module.route)"
              class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
            >
              <svg
                aria-hidden="true"
                class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                  clip-rule="evenodd"
                ></path>
              </svg>

              <span class="ml-3">{{ module.name }}</span>
            </Link>

            <!-- <button
              v-if="module.dropDown"
              type="button"
              class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
              aria-controls="dropdown-pages"
              data-collapse-toggle="dropdown-pages"
            >
              <svg
                aria-hidden="true"
                class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                  clip-rule="evenodd"
                ></path>
              </svg>
              <span class="flex-1 ml-3 text-left whitespace-nowrap"
                >{{ module.name }}</span
              >
              <svg
                aria-hidden="true"
                class="w-6 h-6"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                  clip-rule="evenodd"
                ></path>
              </svg>
            </button> -->

            <!-- <ul id="dropdown-pages" class="hidden py-2 space-y-2" v-if="module.dropDown">
              <li v-for="item in module.subModules" :key="item.name">
                <Link
                  :href="route(item.route)"
                  class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                  >{{ item.name }}
                </Link>
              </li>
            </ul> -->
          </li>
        </ul>
      </div>
    </aside>
</template>
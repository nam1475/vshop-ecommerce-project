<script setup>
import { ref, defineProps, watch, reactive } from 'vue'
import {
  Dialog, DialogPanel, Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems, 
  TransitionChild, TransitionRoot,
} from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { ChevronDownIcon, FunnelIcon, MinusIcon, PlusIcon, Squares2X2Icon } from '@heroicons/vue/20/solid'
import { Link, useForm, router } from '@inertiajs/vue3'
import Main from '@/Pages/Customer/Components/Layout/Main.vue'
import ProductList from '@/Pages/Customer/Components/ProductList.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

const props = defineProps({
  products: Array,
  childrenCategory: Array,
  category: Object,
  brands: Array,
  countProducts: Number
});

const categorySlug = props.category.slug;

const sortOptions = reactive([
  { name: 'Newest', href: '#', type: '' },
  { name: 'Price: Low to High', href: '#', type: 'asc' },
  { name: 'Price: High to Low', href: '#', type: 'desc' },
]);

const subCategories = [
  { name: 'Totes', href: '#' },
  { name: 'Backpacks', href: '#' },
  { name: 'Travel Bags', href: '#' },
  { name: 'Hip Bags', href: '#' },
  { name: 'Laptop Sleeves', href: '#' },
];

const checkedBrands = ref([]);
const checkedCategories = ref([]);

const filters = [
  {
    id: '1',
    name: 'Brands',
    options: props.brands,
    checked: []
  },
  {
    id: '2',
    name: 'Categories',
    options: props.childrenCategory,
    checked: []
  },
];

const filterPrice = useForm({
    // prices: [0, 100000]
    price: {
      from: 0,
      to: 500
    }
});

function priceFilter() {
  filterPrice.transform(data => ({
    // ...data,
    price: {
      from: data.price.from,
      to: data.price.to
    }
  })).get(route('customer.category.list', categorySlug), {
    /* preserveState: Giữ nguyên trạng thái hiện tại của trang (Ko reset lại trang) */
    preserveState: true,
    /* replace: Khi bạn không muốn tạo một mục mới trong lịch sử trình duyệt. Điều này hữu ích khi bạn không muốn 
    người dùng có thể quay lại trang trước sau khi thực hiện một hành động cụ thể. */
    replace: true,
  });
}


watch(checkedBrands, () => {
  updateFilteredProducts();
});
watch(checkedCategories, () => {
  updateFilteredProducts();
});

function updateFilteredProducts(type = '') {
    router.get(route('customer.category.list', categorySlug), 
    {
      brands: checkedBrands.value,
      categories: checkedCategories.value,
       ...(type != '' && { sort: type })
    }, 
    {
      preserveState: true,
      replace: true,
      preserveScroll: true
    }
    );
}

function sortFilters(type, option) {
  router.get(route('customer.category.list', categorySlug), 
  {
    sort: type
  }, 
  {
    replace: true,
    preserveScroll: true,
    // onFinish: () => {
    //   option['current'] = true;
    //   console.log(option);
    // }
  },
  );
}

const mobileFiltersOpen = ref(false);
</script>

<template>
<Main>
  <div class="bg-white">
    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <!-- Children Category -->
      <div class="mx-auto mt-6 max-w-7xl px-4 sm:px-6 lg:px-8" v-if="childrenCategory.length">
        <h2 class="text-4xl font-bold tracking-tight text-gray-900">{{ category.name }}</h2>
        
        <div class="mt-6 space-y-12 lg:grid lg:grid-cols-4 lg:gap-x-6 lg:space-y-0">
          <div class="group relative" v-for="category in childrenCategory" :key="category.id">
            <div class="relative h-80 w-full overflow-hidden rounded-lg bg-white sm:aspect-h-1 sm:aspect-w-2 lg:aspect-h-1 lg:aspect-w-1 group-hover:opacity-75 sm:h-64">
              <img src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg" class="h-full w-full object-cover object-center">
            </div>
            <h3 class="text-base font-bold text-gray-900 mt-3">
              <Link :href="route('customer.category.list', category.slug)">
                <span class="absolute inset-0"></span>
                {{ category.name }}
              </Link>
            </h3>
          </div>
        </div>
      </div>
      
      <!-- Filters -->
      <div class="flex items-baseline justify-between border-b border-gray-200 pb-6 pt-12">
        <h1 class="text-base font-semibold text-gray-500">{{ countProducts }} results</h1>

        <div class="flex items-center">
          <Menu as="div" class="relative inline-block text-left">
            <div>
              <MenuButton class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                Sort
                <ChevronDownIcon class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
              </MenuButton>
            </div>

            <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
              <MenuItems class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none">
                <div class="py-1">
                  <MenuItem v-for="option in sortOptions" :key="option.name" v-slot="{ active }">
                    <a @click="updateFilteredProducts(option.type)" :href="option.href" :class="[option.current ? 'font-medium text-gray-900' : 'text-gray-500', active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm']">
                      {{ option.name }}
                    </a>
                  </MenuItem>
                </div>
              </MenuItems>
            </transition>
          </Menu>
        </div>
      </div>

      <section aria-labelledby="products-heading" class="pb-24 pt-6">
        <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
          <!-- Filters -->
          <form class="hidden lg:block" v-if="category.parent_id != null">
            <!-- Price -->
            <div class="flex items-center justify-between space-x-3">
              <div class="basis-1/3">
                  <label for="filters-price-from"
                      class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                      From
                  </label>

                  <input type="number" id="filters-price-from" placeholder="Min price"
                      v-model="filterPrice.price.from"
                      class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" />
              </div>
              <div class="basis-1/3">
                  <label for="filters-price-to"
                      class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                      To
                  </label>

                  <input type="number" id="filters-price-to" v-model="filterPrice.price.to"
                      placeholder="Max price"
                      class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" />
              </div>
              <SecondaryButton class="self-end" @click="priceFilter()">
                Ok
              </SecondaryButton>
            </div>

            <!-- Other -->
            <!-- <Disclosure as="div" v-for="section in filters" :key="section.id" class="border-b border-gray-200 py-6" v-slot="{ open }">
              <h3 class="-my-3 flow-root">
                <DisclosureButton class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                  <span class="font-medium text-gray-900">{{ section.name }}</span>
                  <span class="ml-6 flex items-center">
                    <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                    <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                  </span>
                </DisclosureButton>
              </h3>
              <DisclosurePanel class="pt-6">
                <div class="space-y-4">
                  <div v-for="(option, optionIdx) in section.options" :key="option.id" class="flex items-center">
                    <input :id="`filter-${section.id}-${optionIdx}`" :value="option.id" v-model="section.checked" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    <label :for="`filter-${section.id}-${optionIdx}`" class="ml-3 text-sm text-gray-600">{{ option.name }}</label>
                  </div>
                </div>
              </DisclosurePanel>
            </Disclosure> -->

            <Disclosure as="div" class="border-b border-gray-200 py-6" v-slot="{ open }" v-if="brands.length">
              <h3 class="-my-3 flow-root">
                <DisclosureButton class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                  <span class="font-medium text-gray-900">Brands</span>
                  <span class="ml-6 flex items-center">
                    <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                    <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                  </span>
                </DisclosureButton>
              </h3>
              <DisclosurePanel class="pt-6">
                <div class="space-y-4">
                  <div v-for="brand in brands" :key="brand.id" class="flex items-center">
                    <input :id="`filter-${brand.id}`" :value="brand.id" v-model="checkedBrands" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    <label :for="`filter-${brand.id}`" class="ml-3 text-sm text-gray-600">{{ brand.name }}</label>
                  </div>
                </div>
              </DisclosurePanel>
            </Disclosure>
            
            <Disclosure as="div" class="border-b border-gray-200 py-6" v-slot="{ open }" v-if="childrenCategory.length">
              <h3 class="-my-3 flow-root">
                <DisclosureButton class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                  <span class="font-medium text-gray-900">Categories</span>
                  <span class="ml-6 flex items-center">
                    <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                    <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                  </span>
                </DisclosureButton>
              </h3>
              <DisclosurePanel class="pt-6">
                <div class="space-y-4">
                  <div v-for="category in childrenCategory" :key="category.id" class="flex items-center">
                    <input :id="`filter-${category.id}`" :value="category.id" v-model="checkedCategories" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    <label :for="`filter-${category.id}`" class="ml-3 text-sm text-gray-600">{{ category.name }}</label>
                  </div>
                </div>
              </DisclosurePanel>
            </Disclosure>
          </form>

          <!-- Product grid -->
          <div :class="category.parent_id == null ? 'lg:col-span-4' : 'lg:col-span-3'">
            <ProductList :products="products" />
          </div>
        </div>
      </section>
    </main>
  </div>
</Main>
</template>


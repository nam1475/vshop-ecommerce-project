<script setup>
import { ref, defineProps, watch, reactive, computed } from 'vue'
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
  products: Object,
  childrenCategory: Array,
  category: Object,
  brands: Array,
  countProducts: Number,
  priceMin: Number,
  priceMax: Number,
});

const categorySlug = props.category.slug;

const sortOptions = reactive([
  { name: 'Newest', type: '' },
  { name: 'Price: Low to High', type: 'asc' },
  { name: 'Price: High to Low', type: 'desc' },
]); 

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

// const filterPrice = useForm({
//   price: {
//     min: 0,  
//     max: 500
//   }
// });

const params = new URLSearchParams(window.location.search);
const queryPriceMax = ref(params.get('price_max'));
const queryPriceMin = ref(params.get('price_min'));
const querySort = ref(params.get('sort'));
const queryBrands = ref(params.getAll('brands[]'));
const queryCategories = ref(params.getAll('categories[]'));

// const checkedBrands = ref([]);
// const checkedCategories = ref([]);

const filterPrice = reactive({
  min: queryPriceMin.value ? queryPriceMin.value : null,  
  max: queryPriceMax.value ? queryPriceMax.value : null
});

// function priceFilter() {
//   filterPrice.transform(data => ({
//     // ...data,
//     price: {
//       min: data.min,
//       max: data.max
//     }
//   })).get(route('customer.category.index', categorySlug), {
//     /* preserveState: Giữ nguyên trạng thái hiện tại của trang (Ko reset lại trang) */
//     preserveState: true,
//     /* replace: Khi bạn không muốn tạo một mục mới trong lịch sử trình duyệt. Điều này hữu ích khi bạn không muốn 
//     người dùng có thể quay lại trang trước sau khi thực hiện một hành động cụ thể. */
//     replace: true,
//     preserveScroll: true
//   });
// }

watch(queryBrands, () => {
  updateFilteredProducts();
});
watch(queryCategories, () => {
  updateFilteredProducts();
});

const price = ref(queryPriceMin.value && queryPriceMax.value ? [queryPriceMin.value, queryPriceMax.value] : [props.priceMin, props.priceMax]);

function updateFilteredProducts(type = '') {
    router.get(route('customer.category.index', categorySlug), 
    {
      brands: queryBrands.value,
      categories: queryCategories.value,
      // price_min: queryPriceMin.value,
      // price_max: queryPriceMax.value,
      price_min: price.value[0],
      price_max: price.value[1],
      // ...(queryPriceMin.value != null && { price_min: queryPriceMin.value }),
      // ...(queryPriceMax.value != null && { price_max: queryPriceMax.value }),
      ...(type != '' && { sort: type })
    }, 
    {
      preserveState: true,
      replace: true,
      preserveScroll: true
    }
    );
}

// function sortFilters(type, option) {
//   router.get(route('customer.category.index', categorySlug), 
//   {
//     sort: type
//   }, 
//   {
//     replace: true,
//     preserveScroll: true,
//     // onFinish: () => {
//     //   option['current'] = true;
//     //   console.log(option);
//     // }
//   },
//   );
// }


</script>

<template>
<Main>
  <div class="bg-white">
    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <!-- Children Category -->
      <div class="mx-auto mt-6 max-w-7xl px-4 sm:px-6 lg:px-8" v-if="childrenCategory.length">
        <h2 class="text-4xl font-bold tracking-tight text-gray-900">{{ category.name }}</h2>
        
        <div class="mt-6 space-y-12 lg:grid lg:grid-cols-4 lg:gap-x-6 lg:space-y-0">
          <div class="group relative mb-6" v-for="category in childrenCategory" :key="category.id">
            <div class="relative h-80 w-full overflow-hidden rounded-lg bg-white sm:aspect-h-1 sm:aspect-w-2 lg:aspect-h-1 lg:aspect-w-1 group-hover:opacity-75 sm:h-64">
              <img v-if="category.images.length" :src="category.images[0]" class="h-full w-full object-cover object-center">
            </div>
            <h3 class="text-base font-bold text-gray-900 mt-3">
              <Link :href="route('customer.category.index', category.slug)">
                <span class="absolute inset-0"></span>
                {{ category.name }}
              </Link>
            </h3>
          </div>
        </div>
      </div>
      
      <!-- Filters -->
      <div class="flex items-baseline justify-between border-b border-gray-200 pb-6 pt-12">
        <div class="flex">
          <h1 class="text-base font-semibold text-gray-500">{{ countProducts }} results</h1>
          <!-- <a href="" v-for=""></a> -->
        </div>

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
                    <button @click="updateFilteredProducts(option.type)" :class="[option.current ? 'font-medium text-gray-900' : 'text-gray-500', active ? 'bg-gray-100' : '', 'block px-3 w-full py-2 text-sm']">
                      {{ option.name }}
                    </button>
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
            <div class="space-x-3">
              <div class="slider-demo-block">
                <div class="flex items-center justify-center">
                  <div>
                    {{ price[0] }}$
                  </div>
                  <div class="mx-2">
                    -
                  </div>
                  <div>
                    {{ price[1] }}$
                  </div>
                </div>
                <el-slider v-model="price" range @change="updateFilteredProducts()" :min="priceMin" :max="priceMax" />
              </div>
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
                    <input :id="`filter-${brand.id}`" :value="brand.slug" v-model="queryBrands" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
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
                    <input :id="`filter-${category.id}`" :value="category.slug" v-model="queryCategories" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
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


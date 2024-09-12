<script setup>
import SingleCategory from '@/Pages/Admin/Category/Single.vue';
import Table from '@/Pages/Admin/Components/Table.vue';
import { defineProps, ref } from 'vue';
import { useStore } from 'vuex';

const props = defineProps({
    categories: Object,
});

const store = useStore();
const isCheckedAll = ref(false); 

function toggleCheckAll() {
  isCheckedAll.value = !isCheckedAll.value;
  if (isCheckedAll.value) {
    setCheckedAllRows(props.categories.data);
  }
  else{
    setCheckedAllRows([]);
  } 
}

function setCheckedAllRows(values) {
  store.dispatch('checkbox/setCheckedAllRows', values);
}

</script>

<template>
<Table routeName="admin.category" :links="categories.links" :filterOptions="categories.data">
    <template #tableHeader>
        <tr>
            <th scope="col" class="p-4">
                <div class="flex items-center">
                    <input id="checkbox-all" v-model="isCheckedAll" @click="toggleCheckAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-all" class="sr-only">checkbox</label>
                </div>  
            </th>
            <th scope="col" class="px-4 py-3">ID</th>
            <th scope="col" class="px-4 py-3">Name</th>
            <th scope="col" class="px-4 py-3">Slug</th>
            <th scope="col" class="px-4 py-3">Parent</th>
            <th scope="col" class="px-4 py-3">
                <span class="sr-only">Actions</span>
            </th>
        </tr>
    </template>
    
    <template #tableBody>
        <SingleCategory v-for="category in categories.data" :key="category.id" :category="category" />
    </template>


</Table>
</template>
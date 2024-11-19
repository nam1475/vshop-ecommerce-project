<script setup>
import SingleCategory from '@/Pages/Admin/Category/Single.vue';
import Table from '@/Pages/Admin/Components/Table.vue';
import { defineProps, ref } from 'vue';
import CheckboxAll from '@/Pages/Admin/Components/CheckboxAll.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  categories: Object,
  title: String
});

function getAllCategories(categories) {
  if (!categories) {
    return [];
  }

  const ids = [...categories];  
  // const ids = categories.map(category => category);  
  // const ids = [categories.id];

  categories.forEach(category => {
    ids.push(...getAllCategories(category.children_recursive));
  });

  return ids;
}


</script>

<template>
<Head :title="title" />
<Table routeName="admin.category" :links="categories.links">
    <template #tableHeader>
      <tr>
        <th scope="col" class="p-4">
          <CheckboxAll :getAllCategories="getAllCategories" :data="categories.data"/>
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
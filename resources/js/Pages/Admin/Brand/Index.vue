<script setup>
import Table from '@/Pages/Admin/Components/Table.vue';
import { defineProps, ref } from 'vue';
import SingleBrand from '@/Pages/Admin/Brand/Single.vue';
import CheckboxAll from '@/Pages/Admin/Components/CheckboxAll.vue';
import { useStore } from 'vuex';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  brands: Object,
  title: String
});

</script>

<template>
<Head :title="title" />
<Table routeName="admin.brand" :links="brands.links" :canFilter="false">
  <template #tableHeader>
    <tr>
      <th scope="col" class="p-4">
        <CheckboxAll :data="brands.data" />
      </th>
      <th scope="col" class="px-4 py-3">ID</th>
      <th scope="col" class="px-4 py-3">Name</th>
      <th scope="col" class="px-4 py-3">Slug</th>
      <th scope="col" class="px-4 py-3">
          <span class="sr-only">Actions</span>
      </th>
    </tr>
  </template>
  
  <template #tableBody>
    <SingleBrand v-for="brand in brands.data" :key="brand.id" :brand="brand" />
  </template>
    
</Table>
</template>
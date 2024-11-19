<script setup>
import SinglePermission from '@/Pages/Admin/Permission/Single.vue';
import Table from '@/Pages/Admin/Components/Table.vue';
import { defineProps, ref } from 'vue';
import CheckboxAll from '@/Pages/Admin/Components/CheckboxAll.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  permissions: Object,
  title: String
});

</script>

<template>
<Head :title="title" />
<Table routeName="admin.permission" :links="permissions.links">
  <template #tableHeader>
    <tr>
      <th scope="col" class="p-4">
        <CheckboxAll :data="permissions.data" />
      </th>
      <th scope="col" class="px-4 py-3">ID</th>
      <th scope="col" class="px-4 py-3">Name</th>
      <th scope="col" class="px-4 py-3">
        <span class="sr-only">Actions</span>
      </th>
    </tr>
  </template>
  
  <template #tableBody>
    <SinglePermission v-for="permission in permissions.data" :key="permission.id" :permission="permission" />
  </template>


</Table>
</template>
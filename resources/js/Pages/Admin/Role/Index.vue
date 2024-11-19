<script setup>
import SingleRole from '@/Pages/Admin/Role/Single.vue';
import Table from '@/Pages/Admin/Components/Table.vue';
import { defineProps, ref } from 'vue';
import CheckboxAll from '@/Pages/Admin/Components/CheckboxAll.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  roles: Object,
  title: String
});

</script>

<template>
<Head :title="title" />
<Table routeName="admin.role" :links="roles.links">
    <template #tableHeader>
      <tr>
        <th scope="col" class="p-4">
          <CheckboxAll :data="roles.data"/>
        </th>
        <th scope="col" class="px-4 py-3">ID</th>
        <th scope="col" class="px-4 py-3">Name</th>
        <th scope="col" class="px-4 py-3">Permissions</th>
        <th scope="col" class="px-4 py-3">
            <span class="sr-only">Actions</span>
        </th>
      </tr>
    </template>
    
    <template #tableBody>
      <SingleRole v-for="role in roles.data" :key="role.id" :role="role" />
    </template>


</Table>
</template>
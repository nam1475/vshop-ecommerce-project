<script setup>
import { defineProps, defineEmits, computed } from 'vue';
import { useStore } from 'vuex';

const props = defineProps({
    data: Object,
});

const store = useStore();
const checkedRows = computed(() => store.getters['checkbox/checkedRows'].includes(props.data.id));

function setCheckedRow(id) {
  store.dispatch('checkbox/setCheckedRow', id);
}

</script>

<template>
    <div class="flex items-center">
        <input :id="`checkbox-${data.id}`" @change="setCheckedRow(data.id)" :checked="checkedRows" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label :for="`checkbox-${data.id}`" class="sr-only">checkbox</label>
    </div>
</template>
<script setup>
import { defineProps, defineEmits, computed } from 'vue';
import { useStore } from 'vuex';

const props = defineProps({
    // data: Object,
    data: {
      type: [String, Number],
      required: true
    },
});

const store = useStore();
// const checkedRows = computed(() => store.getters['checkbox/checkedRows'].includes(props.data.id));
const checkedRows = computed(() => store.getters['checkbox/checkedRows'].includes(props.data));

function setCheckedRow(data) {
  store.dispatch('checkbox/setCheckedRow', data);
}

</script>

<template>
    <div class="flex items-center">
        <!-- <input :id="`checkbox-${data.id}`" @change="setCheckedRow(data.id)" :checked="checkedRows" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"> -->
        <input :id="data" @change="setCheckedRow(data)" :checked="checkedRows" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
    </div>
</template>
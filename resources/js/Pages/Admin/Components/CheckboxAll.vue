<script setup>
import { defineProps, defineEmits, ref} from 'vue';
import { useStore } from 'vuex';

const props = defineProps({
    getAllCategories: Function,
    data: Array,
});

const store = useStore();
const isCheckedAll = ref(false); 

function toggleCheckAll() {
  isCheckedAll.value = !isCheckedAll.value;
  if (isCheckedAll.value) {
    if(props.getAllCategories){
      const ids = props.getAllCategories(props.data);
      setCheckedAllRows(ids);
    }
    else{
      setCheckedAllRows(props.data);
    }
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
    <div class="flex items-center">
        <input id="checkbox-all" :checked="isCheckedAll" @change="toggleCheckAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="checkbox-all" class="sr-only">checkbox</label>
    </div>
</template>
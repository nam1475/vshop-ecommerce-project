<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, defineProps, watch, defineModel, defineEmits } from "vue";

const props = defineProps({
  data: Array,
});

const emit = defineEmits(['update:selectAll']);
const dataForm = defineModel('dataForm');
const selectAll = ref(false);
const indeterminate = ref(false);

watch(dataForm, (newVal) => {
  if (newVal.length == 0) {
    selectAll.value = false;
    indeterminate.value = false;
  } else if (newVal.length == props.data.length) {
    selectAll.value = true;
    indeterminate.value = false;
  } else {
    indeterminate.value = true;
  }
});

function handleSelectAll(isSelectAll) {
  indeterminate.value = false;
  if (isSelectAll) {
    const allSelected = props.data.map((item) => item.id);
    emit('update:selectAll', allSelected);
  } else {
    emit('update:selectAll', []);
  }
}
</script>

<template>
    <el-select
      v-model="dataForm"
      multiple
      clearable
      filterable
      placeholder="Select"
    >
      <template #header>
        <el-checkbox
          v-model="selectAll"
          :indeterminate="indeterminate"
          @change="handleSelectAll"
        >
          All
        </el-checkbox>
      </template>

      <el-option
        v-for="item in data"
        :key="item.id"
        :label="item.name"
        :value="item.id"
      />
    </el-select>
</template>
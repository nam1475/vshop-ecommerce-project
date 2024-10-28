<script setup>
import { computed, onMounted, ref, watch, defineProps, defineEmits, defineModel } from 'vue';
import { useStore } from 'vuex';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
  // selectedProvince: String,
  // selectedDistrict: String,
  // selectedWard: String,
  errors: Object
});

// const emit = defineEmits(['update:selectedProvince', 'update:selectedDistrict', 'update:selectedWard']);

// const localSelectedProvince = ref(props.selectedProvince);
// const localSelectedDistrict = ref(props.selectedDistrict);
// const localSelectedWard = ref(props.selectedWard);

// function emitProvince() {
//   emit('update:selectedProvince', selectedProvince.value.name);
// }

// function emitDistrict() {
//   emit('update:selectedDistrict', localSelectedDistrict.value.name);
// }

// function emitWard() {
//   emit('update:selectedWard', localSelectedWard.value.name);
// }

const selectedProvince = defineModel('selectedProvince');
const selectedDistrict = defineModel('selectedDistrict');
const selectedWard = defineModel('selectedWard');

const store = useStore();
const provinces = computed(() => store.getters['location/provinces']);
const districts = computed(() => store.getters['location/districts']);
const wards = computed(() => store.getters['location/wards']);


function fetchProvinces() {
  store.dispatch('location/fetchProvinces');
}

function fetchDistricts(province) {
  store.dispatch('location/fetchDistricts', province.code);
  // localSelectedDistrict.value = '';
  // localSelectedWard.value = '';
}

function fetchWards(district) {
  store.dispatch('location/fetchWards', district.code);
  // store.dispatch('location/fetchWards', localSelectedDistrict.value.code);
  // localSelectedWard.value = '';
}

watch(selectedProvince, (newVal) => {
  fetchDistricts(newVal);
});

watch(selectedDistrict, (newVal) => {
  fetchWards(newVal);
});

onMounted(() => {
  fetchProvinces();
});

</script>


<template>
  <div class="col-span-1">
    <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
      Province
    </label>
    <!-- <select v-model="localSelectedProvince" @change="emitProvince" class="p-2 w-full"> -->
    <select v-model="selectedProvince" class="p-2 w-full">
      <option value="" disabled>Select province</option>
      <option 
        v-for="province in provinces" 
        :key="province.code" 
        :value="province"
      >
        {{ province.name }}
      </option>
    </select>
    <InputError v-if="errors" :message="errors.province" />
  </div>

  <div class="col-span-1">
    <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
      District
    </label>
    <!-- <select v-model="localSelectedDistrict" @change="emitDistrict" class="p-2 w-full"> -->
    <select v-model="selectedDistrict" class="p-2 w-full">
      <option value="" disabled>Select district</option>
      <option 
        v-for="district in districts" 
        :key="district.code" 
        :value="district"
      >
        {{ district.name }}
      </option>
    </select>
    <InputError v-if="errors" :message="errors.district" />
  </div>

  <div class="col-span-1">
    <label for="website-admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
      Ward
    </label>
    <!-- <select v-model="localSelectedWard" @change="emitWard" class="p-2 w-full"> -->
    <select v-model="selectedWard" class="p-2 w-full">
      <option value="" disabled>Select ward</option>
      <option 
        v-for="ward in wards" 
        :key="ward.code" 
        :value="ward"
      >
        {{ ward.name }}
      </option>
    </select>
    <InputError v-if="errors" :message="errors.ward" />
  </div>
  
</template>



<script setup>
// import axios from "axios";
// import { ref, reactive, watch, onMounted } from "vue";

// const provinces = ref([]);
// const districts = ref([]);
// const wards = ref([]);

// const selectedProvince = ref("");
// const selectedDistrict = ref("");
// const selectedWard = ref("");

// const selectedProvinceName = ref("");
// const selectedDistrictName = ref("");
// const selectedWardName = ref("");

// const fetchProvinces = async () => {
//   try {
//     const response = await axios.get("/api/provinces");
//     provinces.value = response.data;
//   } catch (error) {
//     console.error("Error fetching provinces", error);
//   }
// };

// const fetchDistricts = async () => {
//   try {
//     const response = await axios.get(`/api/districts/${selectedProvince.value}`);
//     districts.value = response.data;
//     selectedProvinceName.value = provinces.value.find(
//       (p) => p.code === selectedProvince.value
//     ).name;
//     selectedDistrict.value = ""; // Reset district when province changes
//     selectedWard.value = ""; // Reset ward when province changes
//   } catch (error) {
//     console.error("Error fetching districts", error);
//   }
// };

// const fetchWards = async () => {
//   try {
//     const response = await axios.get(`/api/wards/${selectedDistrict.value}`);
//     wards.value = response.data;
//     selectedDistrictName.value = districts.value.find(
//       (d) => d.code === selectedDistrict.value
//     ).name;
//     selectedWard.value = ""; // Reset ward when district changes
//   } catch (error) {
//     console.error("Error fetching wards", error);
//   }
// };

// watch(selectedWard, () => {
//   const ward = wards.value.find((w) => w.code === selectedWard.value);
//   if (ward) {
//     selectedWardName.value = ward.name;
//   }
// });

// onMounted(() => {
//   fetchProvinces();
// });


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

const selectedProvince = defineModel('selectedProvince');
const selectedDistrict = defineModel('selectedDistrict');
const selectedWard = defineModel('selectedWard');

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

const store = useStore();
const provinces = computed(() => store.getters['location/provinces']);
const districts = computed(() => store.getters['location/districts']);
const wards = computed(() => store.getters['location/wards']);

// function findProvinceByName(name) {
//   return provinces.value.find((p) => p.name == name);
// }

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



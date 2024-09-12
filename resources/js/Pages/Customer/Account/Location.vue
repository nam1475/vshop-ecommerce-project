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
import { useVModel } from '@vueuse/core';

const props = defineProps({
  selectedProvince: String,
  selectedDistrict: String,
  selectedWard: String,
  test: String
});

const emit = defineEmits(['update:selectedProvince', 'update:selectedDistrict', 'update:selectedWard']);

// const selectedProvince = defineModel('selectedProvince');
// const selectedDistrict = defineModel('selectedDistrict');
// const selectedWard = defineModel('selectedWard');

const localSelectedProvince = ref(props.selectedProvince);
const localSelectedDistrict = ref(props.selectedDistrict);
const localSelectedWard = ref(props.selectedWard);

function emitProvince() {
  emit('update:selectedProvince', localSelectedProvince.value.name);
}

function emitDistrict() {
  emit('update:selectedDistrict', localSelectedDistrict.value.name);
}

function emitWard() {
  emit('update:selectedWard', localSelectedWard.value.name);
}

// watch(() => props.selectedProvince, (newVal) => {
//   localSelectedProvince.value = newVal;
// });

const store = useStore();
const provinces = computed(() => store.getters['location/provinces']);
const districts = computed(() => store.getters['location/districts']);
const wards = computed(() => store.getters['location/wards']);

function findProvinceByName(name) {
  return provinces.value.find((p) => p.name === name);
}

// const selectedProvince = computed(() => store.getters['location/selectedProvince']);
// const selectedDistrict = computed(() => store.getters['location/selectedDistrict']);
// const selectedWard = computed(() => store.getters['location/selectedWard']);
// const selectedProvince = ref(null);
// const selectedDistrict = ref(null);
// const selectedWard = ref(null);

function fetchProvinces() {
  store.dispatch('location/fetchProvinces');
}

function fetchDistricts() {
  store.dispatch('location/fetchDistricts', localSelectedProvince.value.code);
  localSelectedDistrict.value = '';
  localSelectedWard.value = '';
  // store.dispatch('location/fetchDistricts', selectedProvince.value); 
  // store.dispatch('location/setSelectedProvince', selectedProvince.value);
}

function fetchWards() {
  store.dispatch('location/fetchWards', localSelectedDistrict.value.code);
  localSelectedWard.value = '';
  // store.dispatch('location/setSelectedDistrict', selectedDistrict.value);
}

watch(localSelectedProvince, () => {
  fetchDistricts();
});

watch(localSelectedDistrict, () => {
  fetchWards();
});


onMounted(() => {
  fetchProvinces();
});

</script>


<template>
  <div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Chọn địa chỉ</h2>
    
    <div class="mb-4">
      <label for="province" class="block font-medium">Tỉnh/Thành phố</label>
      <!-- <select v-model="selectedProvince" @change="fetchDistricts" class="border p-2 w-full"> -->
      <!-- <select v-model="selectedProvince" @change="fetchDistricts" class="border p-2 w-full"> -->
      <select v-model="localSelectedProvince" @change="emitProvince" class="border p-2 w-full">
        <option value="" disabled>Chọn tỉnh/thành phố</option>
        <option 
          v-for="province in provinces" 
          :key="province.code" 
          :value="province"
        >
          {{ province.name }}
        </option>
      </select>
    </div>

    <div class="mb-4">
      <label for="district" class="block font-medium">Quận/Huyện</label>
      <!-- <select v-model="selectedDistrict" @change="fetchWards" class="border p-2 w-full"> -->
      <select v-model="localSelectedDistrict" @change="emitDistrict" class="border p-2 w-full">
        <option value="" disabled>Chọn quận/huyện</option>
        <option 
          v-for="district in districts" 
          :key="district.code" 
          :value="district"
        >
          {{ district.name }}
        </option>
      </select>
    </div>

    <div>
      <label for="ward" class="block font-medium">Xã/Phường</label>
      <select v-model="localSelectedWard" @change="emitWard" class="border p-2 w-full">
        <option value="" disabled>Chọn xã/phường</option>
        <option 
          v-for="ward in wards" 
          :key="ward.code" 
          :value="ward"
        >
          {{ ward.name }}
        </option>
      </select>
    </div>

    <!-- <p class="mt-4">Địa chỉ đã chọn: {{ selectedProvinceName }}, {{ selectedDistrictName }}, {{ selectedWardName }}</p> -->
    <!-- <p class="mt-4">Địa chỉ đã chọn: {{ selectedProvince }}, {{ selectedDistrict }}, {{ selectedWard }}</p> -->
  </div>

  <!-- <div>
    <div>
      <label for="province">Tỉnh/Thành phố:</label>
      <select id="province" v-model="selectedProvince" @change="fetchDistricts">
        <option value="" disabled>Chọn Tỉnh/Thành phố</option>
        <option v-for="province in provinces" :key="province.code" :value="province.code">
          {{ province.name }}
        </option>
      </select>
    </div>

    <div v-if="selectedProvince">
      <label for="district">Quận/Huyện:</label>
      <select id="district" v-model="selectedDistrict" @change="fetchWards">
        <option value="" disabled>Chọn Quận/Huyện</option>
        <option v-for="district in districts" :key="district.code" :value="district.code">
          {{ district.name }}
        </option>
      </select>
    </div>

    <div v-if="selectedDistrict">
      <label for="ward">Xã/Phường:</label>
      <select id="ward" v-model="selectedWard">
        <option value="" disabled>Chọn Xã/Phường</option>
        <option v-for="ward in wards" :key="ward.code" :value="ward.code">
          {{ ward.name }}
        </option>
      </select>
    </div>
  </div> -->
</template>



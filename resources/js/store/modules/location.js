import axios from 'axios';

export default {
  namespaced: true,
  
  state: {
    provinces: [],
    districts: [],
    wards: [],
    selectedProvince: null,
    selectedDistrict: null,
    selectedWard: null,
  },

  getters: {
    provinces: (state) => state.provinces,
    districts: (state) => state.districts,
    wards: (state) => state.wards,
    
    selectedProvince: (state) => state.selectedProvince,
    selectedDistrict: (state) => state.selectedDistrict,
    selectedWard: (state) => state.selectedWard,
  },

  mutations: {
    SET_PROVINCES(state, provinces) {
      state.provinces = provinces;
    },
    SET_DISTRICTS(state, districts) {
      state.districts = districts;
    },
    SET_WARDS(state, wards) {
      state.wards = wards;
    },

    SET_SELECTED_PROVINCE(state, province) {
      state.selectedProvince = province;
    },
    SET_SELECTED_DISTRICT(state, district) {
      state.selectedDistrict = district;
    },
    SET_SELECTED_WARD(state, ward) {
      state.selectedWard = ward;
    },
  },

  actions: {
    async fetchProvinces({ commit }) {
      try {
        const response = await axios.get('/api/provinces');
        commit('SET_PROVINCES', response.data);
      } catch (error) {
        console.error(error);
      }
    },
    async fetchDistricts({ commit }, provinceCode) {
      try {
        const response = await axios.get(`/api/districts/${provinceCode}`);
        commit('SET_DISTRICTS', response.data);
      } catch (error) {
        console.error(error);
      }
    },
    async fetchWards({ commit }, districtCode) {
      try {
        const response = await axios.get(`/api/wards/${districtCode}`);
        commit('SET_WARDS', response.data);
      } catch (error) {
        console.error(error);
      }
    },

    setSelectedProvince({ commit }, province) {
      commit('SET_SELECTED_PROVINCE', province);
    },
    setSelectedDistrict({ commit }, district) {
      commit('SET_SELECTED_DISTRICT', district);
    },
    setSelectedWard({ commit }, ward) {
      commit('SET_SELECTED_WARD', ward);
    },
  },


};
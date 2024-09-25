import axios from 'axios';

export default {
  namespaced: true,

  state: {
    checkedRows: [],
  },

  getters: {
    checkedRows: (state) => state.checkedRows,
  },

  mutations: {
    SET_CHECKED_ROW(state, item) {
      // state.checkedRows.splice(0, 0, ...item);
      /* Loại bỏ phần tử trùng lặp */
      // state.checkedRows = [...new Set([...state.checkedRows, ...item])];
      // state.checkedRows = state.checkedRows.concat(item);
      // console.log(state.checkedRows);
      
      const index = state.checkedRows.indexOf(item);
      
      if (index > -1) {
        state.checkedRows.splice(index, 1);
      } 
      else {
        state.checkedRows.push(item);
      }
      console.log(state.checkedRows);
      
    },

    SET_CHECKED_ALL_ROWS(state, items) {
      const isArrayOfObjects = items.every(item => typeof item === 'object' && item != null);
      if(isArrayOfObjects) {
        state.checkedRows = items.map(item => item.id);
        console.log(state.checkedRows);
      }
      else{
        state.checkedRows = items;
        console.log(state.checkedRows);
      }
    },
  },

  actions: {
    setCheckedRow({ commit }, item) {
      try {
        commit('SET_CHECKED_ROW', item); 
      } catch (error) {
        console.error(error);
      }
    },

    setCheckedAllRows({ commit }, items) {
      try {
        commit('SET_CHECKED_ALL_ROWS', items); 
      } catch (error) {
        console.error(error);
      }
    }
  },

}


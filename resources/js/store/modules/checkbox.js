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
      console.log(index);
      console.log(item);
      
      if (index > -1) {
        state.checkedRows.splice(index, 1);
      } 
      else {
        state.checkedRows.push(item);
      }
      console.log(state.checkedRows);
      
      // item.forEach(value => {
      //   const index = state.checkedRows.indexOf(value);
      //   if (index > -1) {
      //     state.checkedRows.splice(index, 1);
      //   } 
      //   else {
      //     state.checkedRows.push(value);
      //   }
      // });
      
    },

    SET_CHECKED_ALL_ROWS(state, items) {
      // state.checkedRows = items;
      // console.log(state.checkedRows);
      
      state.checkedRows = items.map(item => item.id);
      console.log(state.checkedRows);
      

      // items.forEach(item => {
      //   if(!state.checkedRows.includes(item)) {
      //     state.checkedRows.push(item);
      //   }
      //   else{
      //     const index = state.checkedRows.indexOf(item);
      //     state.checkedRows.splice(index, 1);
      //   }
      //   console.log(state.checkedRows);
        
      // });
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


import { createStore } from 'vuex'
import location from './modules/location';
import checkbox from './modules/checkbox';

// Create a new store instance.
const store = createStore({
  modules: {
    location,
    checkbox
  },
});

export default store;

import Vue from 'vue';
import router from './router';
import Redis from './redis';
import './elementui';
import App from './components/App.vue';

Vue.prototype.$redis = Redis.create();

window.Bus = new Vue({ name: 'Bus' });

const app = new Vue({
  el: '#app',
  router,
  render: h => h(App),
});

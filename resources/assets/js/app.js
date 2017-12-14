
import Vue from 'vue';
import router from './router';
import redis from './redis';
import App from './components/App.vue';

Vue.prototype.$redis = redis;

window.Bus = new Vue({ name: 'Bus' });

require('./elementui');

const app = new Vue({
  el: '#app',
  router,
  render: h => h(App),
});


import Vue from 'vue';
import _ from 'lodash';
// import axios from 'axios';
import router from './router';
import redis from './redis';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import App from './components/App.vue';

// Vue.prototype.$http = axios.create();
Vue.prototype.$redis = redis;

window.Bus = new Vue({ name: 'Bus' });

Vue.use(ElementUI);

const app = new Vue({
  el: '#app',
  router,
  render: h => h(App),
});


import Vue from 'vue';
import _ from 'lodash';

import router from './router';
import redis from './redis';
import ElementUI from 'element-ui';
import App from './components/App.vue';

Vue.prototype.$redis = redis;

window.Bus = new Vue({ name: 'Bus' });

Vue.use(ElementUI);

const app = new Vue({
  el: '#app',
  router,
  render: h => h(App),
});

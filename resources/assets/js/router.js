import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
  mode: 'history',
  base: '/redis-manager/',
  routes: [
    {
      path: '/',
      component: require('./pages/Keys.vue'),
    },
    {
      path: '/create',
      component: require('./pages/create/Index.vue'),
      children: [
        { path: '/', redirect: 'string' },
        { path: 'string', component: require('./pages/create/String.vue') },
        { path: 'hash', component: require('./pages/create/Hash.vue') },
        { path: 'list', component: require('./pages/create/List.vue') },
        { path: 'set', component: require('./pages/create/Set.vue') },
        { path: 'zset', component: require('./pages/create/Zset.vue') },
      ],
    },
    {
      path: '/edit',
      component: require('./pages/edit/Index.vue'),
      children: [
        { path: '/', redirect: 'string' },
        { path: 'string', component: require('./pages/edit/String.vue') },
        { path: 'hash', component: require('./pages/edit/Hash.vue') },
        { path: 'list', component: require('./pages/edit/List.vue') },
        { path: 'set', component: require('./pages/edit/Set.vue') },
        { path: 'zset', component: require('./pages/edit/Zset.vue') },
      ],
    },
    {
      path: '/info',
      component: require('./pages/Info.vue'),
    },
    {
      path: '/console',
      component: require('./pages/Console.vue'),
    },

    {
      path: '/metrics',
      component: require('./pages/metrics/Index.vue'),
      children: [{
        path: '/',
        redirect: 'memory',
      }, {
        path: 'memory',
        component: require('./pages/metrics/Memory.vue'),
      },
      {
        path: 'cpu',
        component: require('./pages/metrics/Cpu.vue'),
      },
      {
        path: 'clients',
        component: require('./pages/metrics/Clients.vue'),
      },
      {
        path: 'throughput',
        component: require('./pages/metrics/Throughput.vue'),
      },],
    },
  ],
});

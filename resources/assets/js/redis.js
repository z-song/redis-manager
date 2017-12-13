
import axios from 'axios';

export default {

  $http: axios.create(),

  conn: 'default',

  getConnection() {
    return localStorage.getItem('conn') || 'default'
  },

  connections() {
    return this.$http.get('/redis-manager/api/connections');
  },

  info(section = null) {
    return this.$http.get('/redis-manager/api/info', {
      params: {section, conn: this.getConnection() },
    });
  },

  expire(key, seconds) {
    const params = {
      key,
      seconds,
      conn: this.getConnection()
    }

    return this.$http.put('/redis-manager/api/expire', params);
  },

  get(key) {
    return this.$http.get('/redis-manager/api/key', {
      params: { key, conn: this.getConnection() },
    });
  },

  del(keys) {
    return this.$http.delete('/redis-manager/api/keys', {
      params: { keys, conn: this.getConnection() },
    });
  },

  set(key, value) {
    const params = {
      type: 'string',
      key,
      value,
      conn: this.getConnection(),
    };

    return this.$http.post('/redis-manager/api/keys', params);
  },

  setex(key, seconds, value) {
    const params = {
      type: 'string',
      key,
      value,
      seconds,
      conn: this.getConnection(),
    };

    return this.$http.post('/redis-manager/api/keys', params);
  },

  hgetall(key) {
    return this.get(key);
  },

  hmset(key, dic, seconds = null) {
    const params = {
      type: 'hash',
      key,
      dic,
      seconds,
      conn: this.getConnection(),
    };

    return this.$http.post('/redis-manager/api/keys', params);
  },

  hset(key, name, value) {
    const dic = {
      name,
      value,
    };

    return this.hmset(key, [dic], null);
  },

  hdel(key, field) {

    const type = 'hash'
    const conn = this.getConnection()

    return this.$http.delete('/redis-manager/api/keys/item', {
      params: { key, field, type, conn },
    });
  },

  smembers(key) {
    return this.get(key);
  },

  sadd(key, members, seconds) {
    const params = {
      type: 'set',
      key,
      members,
      seconds,
      conn: this.getConnection(),
    };

    return this.$http.post('/redis-manager/api/keys', params);
  },

  srem(key, member) {
    const params = {
      type: 'set',
      action: 'srem',
      key,
      member,
      conn: this.getConnection(),
    };

    return this.$http.put('/redis-manager/api/keys', params);
  },

  lall(key) {
    return this.get(key)
  },

  lpush(key, members, seconds) {
    const params = {
      type: 'list',
      action: 'lpush',
      key,
      members,
      seconds,
      conn: this.getConnection(),
    };

    return this.$http.put('/redis-manager/api/keys', params);
  },

  rpush(key, members, seconds) {
    const params = {
      type: 'list',
      action: 'rpush',
      key,
      members,
      seconds,
      conn: this.getConnection(),
    };

    return this.$http.put('/redis-manager/api/keys', params);
  },

  ldel(key, index) {

    const type = 'list'
    const conn = this.getConnection()

    return this.$http.delete('/redis-manager/api/keys/item', {
      params: { key, index, type, conn },
    });
  },

  lset(key, index, value) {
    const params = {
      type: 'list',
      action: 'lset',
      key,
      value,
      index,
      conn: this.getConnection(),
    };

    return this.$http.put('/redis-manager/api/keys', params);
  },

  zall(key) {
    return this.get(key);
  },

  zadd(key, members, seconds = null) {
    const params = {
      type: 'zset',
      key,
      members,
      seconds,
      conn: this.getConnection(),
    };

    return this.$http.post('/redis-manager/api/keys', params);
  },

  zrem(key, member) {
    const params = {
      type: 'zset',
      action: 'zrem',
      key,
      member,
      conn: this.getConnection(),
    };

    return this.$http.put('/redis-manager/api/keys', params);
  },

  zset(key, member, score) {
    const params = {
      type: 'zset',
      action: 'zset',
      key,
      member,
      score,
      conn: this.getConnection(),
    };

    return this.$http.put('/redis-manager/api/keys', params);
  },

  scan(pattern, count = 50) {
    const params = {
      pattern,
      count,
      conn: this.getConnection(),
    };

    return this.$http.get('/redis-manager/api/scan', { params });
  },

  eval(command, db = null) {
    const params = {
      command,
      db,
      conn: this.getConnection(),
    };

    return this.$http.post('/redis-manager/api/eval', params);
  },

};

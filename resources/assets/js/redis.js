
import axios from 'axios';

export default class {

  constructor() {
      this.$http = axios.create({baseURL: window.basePath + '/api/'});

    this.conn = 'default';
  }

  static create() {
    return new this;
  }

  getConnection() {
    return localStorage.getItem('conn') || 'default'
  }

  connections() {
    return this.$http.get('/connections');
  }

  info(section = null) {
    return this.$http.get('/info', {
      params: {section, conn: this.getConnection() },
    });
  }

  expire(key, seconds) {
    const params = {
      key,
      seconds,
      conn: this.getConnection()
    }

    return this.$http.put('/expire', params);
  }

  get(key) {
    return this.$http.get('/key', {
      params: { key, conn: this.getConnection() },
    });
  }

  del(keys) {
    return this.$http.delete('/keys', {
      params: { keys, conn: this.getConnection() },
    });
  }

  set(key, value) {
    const params = {
      type: 'string',
      key,
      value,
      conn: this.getConnection(),
    };

    return this.$http.post('/keys', params);
  }

  setex(key, seconds, value) {
    const params = {
      type: 'string',
      key,
      value,
      seconds,
      conn: this.getConnection(),
    };

    return this.$http.post('/keys', params);
  }

  hgetall(key) {
    return this.get(key);
  }

  hmset(key, dic, seconds = null) {
    const params = {
      type: 'hash',
      key,
      dic,
      seconds,
      conn: this.getConnection(),
    };

    return this.$http.post('/keys', params);
  }

  hset(key, name, value) {
    const dic = {
      name,
      value,
    };

    return this.hmset(key, [dic], null);
  }

  hdel(key, field) {

    const type = 'hash'
    const conn = this.getConnection()

    return this.$http.delete('/keys/item', {
      params: { key, field, type, conn },
    });
  }

  smembers(key) {
    return this.get(key);
  }

  sadd(key, members, seconds) {
    const params = {
      type: 'set',
      key,
      members,
      seconds,
      conn: this.getConnection(),
    };

    return this.$http.post('/keys', params);
  }

  srem(key, member) {
    const params = {
      type: 'set',
      action: 'srem',
      key,
      member,
      conn: this.getConnection(),
    };

    return this.$http.put('/keys', params);
  }

  lall(key) {
    return this.get(key)
  }

  lpush(key, members, seconds) {
    const params = {
      type: 'list',
      action: 'lpush',
      key,
      members,
      seconds,
      conn: this.getConnection(),
    };

    return this.$http.put('/keys', params);
  }

  rpush(key, members, seconds) {
    const params = {
      type: 'list',
      action: 'rpush',
      key,
      members,
      seconds,
      conn: this.getConnection(),
    };

    return this.$http.put('/keys', params);
  }

  lstore(key, members, seconds) {
    const params = {
      type: 'list',
      action: 'rpush',
      key,
      members,
      seconds,
      conn: this.getConnection(),
    };

    return this.$http.post('/keys', params);
  }
  
  ldel(key, index) {

    const type = 'list'
    const conn = this.getConnection()

    return this.$http.delete('/keys/item', {
      params: { key, index, type, conn },
    });
  }

  lset(key, index, value) {
    const params = {
      type: 'list',
      action: 'lset',
      key,
      value,
      index,
      conn: this.getConnection(),
    };

    return this.$http.put('/keys', params);
  }

  zall(key) {
    return this.get(key);
  }

  zadd(key, members, seconds = null) {
    const params = {
      type: 'zset',
      key,
      members,
      seconds,
      conn: this.getConnection(),
    };

    return this.$http.post('/keys', params);
  }

  zrem(key, member) {
    const params = {
      type: 'zset',
      action: 'zrem',
      key,
      member,
      conn: this.getConnection(),
    };

    return this.$http.put('/keys', params);
  }

  zset(key, member, score) {
    const params = {
      type: 'zset',
      action: 'zset',
      key,
      member,
      score,
      conn: this.getConnection(),
    };

    return this.$http.put('/keys', params);
  }

  scan(pattern) {
    const params = {
      pattern,
      conn: this.getConnection(),
    };

    return this.$http.get('/scan', { params });
  }

  eval(command, db = null) {
    const params = {
      command,
      db,
      conn: this.getConnection(),
    };

    return this.$http.post('/eval', params);
  }

}

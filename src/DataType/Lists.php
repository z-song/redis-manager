<?php

namespace Encore\RedisManager\DataType;

class Lists extends DataType
{
    /**
     * {@inheritdoc}
     */
    public function fetch(string $key)
    {
        return $this->getConnection()->lrange($key, 0, -1);
    }

    /**
     * {@inheritdoc}
     */
    public function update(array $params)
    {
        $key = array_get($params, 'key');

        $action = array_get($params, 'action');

        if (in_array($action, ['lpush', 'rpush'])) {
            $members = array_get($params, 'members');
            $this->getConnection()->{$action}($key, $members);
        }

        if ($action == 'lset') {
            $value = array_get($params, 'value');
            $index = array_get($params, 'index');

            $this->getConnection()->lset($key, $index, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function store(array $params)
    {
        $key      = array_get($params, 'key');
        $members  = array_get($params, 'members');
        $expire   = array_get($params, 'expire');
        $action   = array_get($params, 'action', 'rpush');

        $members = array_column($members, 'value');

        $this->getConnection()->{$action}($key, $members);

        if ($expire > 0) {
            $this->getConnection()->expire($key, $expire);
        }
    }

    /**
     * Remove a member from list by index.
     *
     * @param array $params
     * @return mixed
     */
    public function remove(array $params)
    {
        $key   = array_get($params, 'key');
        $index = array_get($params, 'index');

        $lua = <<<'LUA'
redis.call('lset', KEYS[1], ARGV[1], '__DELETED__');
redis.call('lrem', KEYS[1], 1, '__DELETED__');
LUA;

        return $this->getConnection()->eval($lua, 1, $key, $index);
    }
}

<?php

namespace Encore\RedisManager\DataType;

class Sets extends DataType
{
    /**
     * {@inheritdoc}
     */
    public function fetch(string $key)
    {
        return $this->getConnection()->smembers($key);
    }

    /**
     * {@inheritdoc}
     */
    public function update(array $params)
    {
        $key      = array_get($params, 'key');
        $member   = array_get($params, 'member');
        $action   = array_get($params, 'action');

        if ($action === 'srem') {
            $this->getConnection()->srem($key, $member);
        }

        if ($action === 'sadd') {
            $this->getConnection()->sadd($key, [$member]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function store(array $params)
    {
        $key       = array_get($params, 'key');
        $members   = array_get($params, 'members');
        $seconds   = array_get($params, 'seconds');

        $this->getConnection()->sadd($key, $members);

        if ($seconds > 0) {
            $this->getConnection()->expire($key, $seconds);
        } else {
            $this->getConnection()->persist($key);
        }
    }

    /**
     * Remove a member from a set.
     *
     * @param array $params
     * @return int
     */
    public function remove(array $params)
    {
        $key    = array_get($params, 'key');
        $member = array_get($params, 'member');

        return $this->getConnection()->srem($key, $member);
    }
}

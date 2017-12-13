<?php

namespace Encore\RedisManager\DataType;

class SortedSets extends DataType
{
    /**
     * {@inheritdoc}
     */
    public function fetch(string $key)
    {
        return $this->getConnection()->zrange($key,0, -1, ['WITHSCORES' => true]);
    }

    public function update(array $params)
    {
        $key      = array_get($params, 'key');
        $member   = array_get($params, 'member');
        $action   = array_get($params, 'action');

        if ($action === 'zrem') {
            $this->getConnection()->zrem($key, $member);
        }

        if ($action === 'zset') {
            $score = array_get($params, 'score');
            $this->getConnection()->zadd($key, [$member => $score]);
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

        $fields = [];

        foreach ($members as $member) {
            $fields[$member['member']] = $member['score'];
        }

        $this->getConnection()->zadd($key, $fields);

        if ($expire > 0) {
            $this->getConnection()->expire($key, $expire);
        }
    }

    /**
     * Remove a member from a sorted set.
     *
     * @param array $params
     * @return int
     */
    public function remove(array $params)
    {
        $key   = array_get($params, 'key');
        $member = array_get($params, 'member');

        return $this->getConnection()->zrem($key, $member);
    }
}
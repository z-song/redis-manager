<?php

namespace Encore\RedisManager\DataType;

class Strings extends DataType
{
    /**
     * {@inheritdoc}
     */
    public function fetch(string $key)
    {
        return $this->getConnection()->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function update(array $params)
    {
        $this->store($params);
    }

    /**
     * {@inheritdoc}
     */
    public function store(array $params)
    {
        $key        = array_get($params, 'key');
        $value      = array_get($params, 'value');
        $seconds    = array_get($params, 'seconds');

        $this->getConnection()->set($key, $value);

        if ($seconds > 0) {
            $this->getConnection()->expire($key, $seconds);
        }
    }
}
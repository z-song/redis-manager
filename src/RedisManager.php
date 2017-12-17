<?php

namespace Encore\RedisManager;

use Encore\RedisManager\DataType\DataType;
use Encore\RedisManager\DataType\Hashes;
use Encore\RedisManager\DataType\Lists;
use Encore\RedisManager\DataType\Sets;
use Encore\RedisManager\DataType\SortedSets;
use Encore\RedisManager\DataType\Strings;
use Encore\RedisManager\Formatter\Information;
use Illuminate\Http\Request;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use Predis\Collection\Iterator\Keyspace;
use Predis\Pipeline\Pipeline;

/**
 * Class RedisManager.
 */
class RedisManager
{
    /**
     * @var array
     */
    protected $dataTyps = [
        'string' => Strings::class,
        'hash'   => Hashes::class,
        'set'    => Sets::class,
        'zset'   => SortedSets::class,
        'list'   => Lists::class,
    ];

    /**
     * @var RedisManager
     */
    protected static $instance;

    /**
     * @var string
     */
    protected $connection;

    /**
     * The callback that should be used to authenticate redis-manager users.
     *
     * @var \Closure
     */
    public static $authUsing;

    /**
     * Get instance of redis manager.
     *
     * @param string $connection
     *
     * @return RedisManager
     */
    public static function instance($connection = 'default')
    {
        if (!static::$instance instanceof self) {
            static::$instance = new static($connection);
        }

        return static::$instance;
    }

    /**
     * RedisManager constructor.
     *
     * @param string $connection
     */
    public function __construct($connection = 'default')
    {
        $this->connection = $connection;
    }

    /**
     * Determine if the given request can access redis-manager.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public static function check($request)
    {
        return (static::$authUsing ?: function () {
            return app()->environment('local');
        })($request);
    }

    /**
     * Set the callback that should be used to authenticate redis-manager users.
     *
     * @param \Closure $callback
     *
     * @return static
     */
    public static function auth(\Closure $callback)
    {
        static::$authUsing = $callback;

        return new static();
    }

    /**
     * @return Lists
     */
    public function list()
    {
        return new Lists($this->getConnection());
    }

    /**
     * @return Strings
     */
    public function string()
    {
        return new Strings($this->getConnection());
    }

    /**
     * @return Hashes
     */
    public function hash()
    {
        return new Hashes($this->getConnection());
    }

    /**
     * @return Sets
     */
    public function set()
    {
        return new Sets($this->getConnection());
    }

    /**
     * @return SortedSets
     */
    public function zset()
    {
        return new SortedSets($this->getConnection());
    }

    /**
     * Get connection collections.
     *
     * @return Collection
     */
    public function getConnections()
    {
        return collect(config('database.redis'))->filter(function ($conn) {
            return is_array($conn);
        });
    }

    /**
     * Get a registered connection instance.
     *
     * @param string $connection
     *
     * @return Connection
     */
    public function getConnection($connection = null)
    {
        if ($connection) {
            $this->connection = $connection;
        }

        return Redis::connection($this->connection);
    }

    /**
     * Get information of redis instance.
     *
     * @param mixed $section
     *
     * @return array
     */
    public function getInformation($section = null)
    {
        if ($section) {
            $info = $this->getConnection()->info($section);

            return Information::$section($info);
        }

        return $this->getConnection()->info();
    }

    /**
     * Scan keys in redis by giving pattern.
     *
     * @param string $pattern
     * @param int    $count
     *
     * @return array|\Predis\Pipeline\Pipeline
     */
    public function scan($pattern = '*', $count = 100)
    {
        $client = $this->getConnection();
        $keys = [];

        foreach (new Keyspace($client->client(), $pattern) as $item) {
            $keys[] = $item;

            if (count($keys) == $count) {
                break;
            }
        }

        $script = <<<'LUA'
        local type = redis.call('type', KEYS[1])
        local ttl = redis.call('ttl', KEYS[1])

        return {KEYS[1], type, ttl}
LUA;

        $keys = $client->pipeline(function (Pipeline $pipe) use ($keys, $script) {
            foreach ($keys as $key) {
                $pipe->eval($script, 1, $key);
            }
        });

        return collect($keys)->map(function ($key) {
            return [
                'key'  => $key[0],
                'type' => (string) $key[1],
                'ttl'  => $key[2],
            ];
        });
    }

    /**
     * Fetch value of a giving key.
     *
     * @param string $key
     *
     * @return array
     */
    public function fetch($key)
    {
        if (!$this->getConnection()->exists($key)) {
            return [];
        }

        $type = $this->getConnection()->type($key)->__toString();

        /** @var DataType $class */
        $class = $this->{$type}();

        $value = $class->fetch($key);
        $expire = $class->ttl($key);

        return compact('key', 'value', 'expire', 'type');
    }

    /**
     * Update a specified key.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function update(Request $request)
    {
        $key = $request->get('key');
        $type = $request->get('type');

        /** @var DataType $class */
        $class = $this->{$type}();

        $class->update($request->all());

        $class->setTtl($key, $request->get('ttl'));
    }

    /**
     * Remove the specified key.
     *
     * @param array $keys
     *
     * @return int
     */
    public function del($keys)
    {
        return $this->getConnection()->del($keys);
    }

    /**
     * 运行redis命令.
     *
     * @param string $command
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function execute($command, $db)
    {
        $command = explode(' ', trim($command));

        if ($this->commandDisabled($command[0])) {
            throw new \Exception("Command [{$command[0]}] is disabled!");
        }

        $client = $this->getConnection();

        if ($db !== null) {
            $client->select($db);
        }

        return $client->executeRaw($command);
    }

    /**
     * Determine if giving command is disabled.
     *
     * @param string $command
     *
     * @return bool
     */
    protected function commandDisabled(string $command)
    {
        $disabled = config('redis-manager.disable_commands');

        $disabled = array_map('strtoupper', (array) $disabled);

        return in_array(strtoupper($command), $disabled);
    }

    /**
     * @param $key
     * @param int $seconds
     *
     * @return int
     */
    public function expire($key, $seconds = -1)
    {
        if ($seconds > 0) {
            return $this->getConnection()->expire($key, $seconds);
        } else {
            return $this->getConnection()->persist($key);
        }
    }
}

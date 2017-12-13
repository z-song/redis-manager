<?php

namespace Encore\RedisManager\Http;

use Encore\RedisManager\RedisManager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RedisController extends BaseController
{
    /**
     * Index page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('laravel-redis-manager::app');
    }

    /**
     * Get redis connections.
     *
     * @return Collection
     */
    public function connections()
    {
        $config = config('database.redis');

        return collect($config)->filter(function ($conn) {
            return is_array($conn);
        })->keys();
    }

    /**
     * @param Request $request
     * @return array|\Predis\Pipeline\Pipeline
     */
    public function scan(Request $request)
    {
        $manager = $this->manager();

        return $manager->scan(
            $request->get('pattern', '*'),
            $request->get('count', 50)
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    public function info(Request $request)
    {
        $section = $request->get('section');

        return $this->manager()->getInformation($section);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $type = $request->get('type');

        return $this->manager()->{$type}()->store($request->all());
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    public function destroy(Request $request)
    {
        return $this->manager()->del($request->get('keys'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function key(Request $request)
    {
        return $this->manager()->fetch($request->get('key'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function remove(Request $request)
    {
        $type = $request->get('type');

        return $this->manager()->{$type}()->remove($request->all());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        return $this->manager()->update($request);
    }

    /**
     * @param Request $request
     * @return int
     */
    public function expire(Request $request)
    {
        return $this->manager()->expire($request->get('key'), $request->get('seconds'));
    }

    /**
     * Execute a redis command
     *
     * @param Request $request
     *
     * @return array
     */
    public function eval(Request $request)
    {
        $command = $request->get('command');
        $db      = $request->get('db');

        try {
            $result = $this->manager()->execute($command, $db);
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'data'    => $exception->getMessage(),
                'command' => $command
            ];
        }

        if (is_string($result) && Str::startsWith($result, ['ERR ', 'WRONGTYPE '])) {
            return [
                'success' => false,
                'data'    => $result,
                'command' => $command
            ];
        }

        return [
            'success' => true,
            'data'  => $result,
            'command' => $command
        ];
    }

    /**
     * Get the redis manager instance.
     *
     * @return RedisManager
     */
    protected function manager()
    {
        $conn = \request()->get('conn');

        return RedisManager::instance($conn);
    }
}
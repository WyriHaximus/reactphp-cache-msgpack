<?php declare(strict_types=1);

namespace WyriHaximus\React\Cache;

use React\Cache\CacheInterface;
use React\Promise\PromiseInterface;

final class Msgpack implements CacheInterface
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param $key
     * @param  null             $default
     * @return PromiseInterface
     */
    public function get($key, $default = null)
    {
        return $this->cache->get($key, $default)->then(function ($result) use ($default) {
            if ($result === null || $result === $default) {
                return $result;
            }

            return msgpack_unpack($result);
        });
    }

    /**
     * @param  string           $key
     * @param  mixed            $value
     * @param  null             $ttl
     * @return PromiseInterface
     */
    public function set($key, $value, $ttl = null)
    {
        return $this->cache->set($key, msgpack_pack($value), $ttl);
    }

    /**
     * @param  string           $key
     * @return PromiseInterface
     */
    public function delete($key)
    {
        return $this->cache->delete($key);
    }
}

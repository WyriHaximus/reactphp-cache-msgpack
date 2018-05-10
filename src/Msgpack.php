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
     * @param  string           $key
     * @return PromiseInterface
     */
    public function get($key)
    {
        return $this->cache->get($key)->then(function ($result) {
            return msgpack_unpack($result, true);
        });
    }

    /**
     * @param  string           $key
     * @param  mixed            $value
     * @return PromiseInterface
     */
    public function set($key, $value)
    {
        return $this->cache->set($key, msgpack_pack($value));
    }

    /**
     * @param  string           $key
     * @return PromiseInterface
     */
    public function remove($key)
    {
        return $this->cache->remove($key);
    }
}

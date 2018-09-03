<?php declare(strict_types=1);

namespace WyriHaximus\Tests\React\Cache;

use ApiClients\Tools\TestUtilities\TestCase;
use React\Cache\CacheInterface;
use WyriHaximus\React\Cache\Msgpack;
use function React\Promise\resolve;

final class MsgpackTest extends TestCase
{
    public function testGet()
    {
        $key = 'sleutel';
        $json = [
            'foo' => 'bar',
        ];
        $string = msgpack_pack($json);

        $cache = $this->prophesize(CacheInterface::class);
        $cache->get($key, null)->shouldBeCalled()->willReturn(resolve($string));

        $jsonCache = new Msgpack($cache->reveal());
        self::assertSame($json, $this->await($jsonCache->get($key)));
    }

    public function testGetNullShouldBeIgnored()
    {
        $key = 'sleutel';

        $cache = $this->prophesize(CacheInterface::class);
        $cache->get($key, null)->shouldBeCalled()->willReturn(resolve(null));

        $jsonCache = new Msgpack($cache->reveal());
        self::assertNull($this->await($jsonCache->get($key)));
    }

    public function testSet()
    {
        $key = 'sleutel';
        $json = [
            'foo' => 'bar',
        ];
        $string = msgpack_pack($json);

        $cache = $this->prophesize(CacheInterface::class);
        $cache->set($key, $string, null)->shouldBeCalled();

        $jsonCache = new Msgpack($cache->reveal());
        $jsonCache->set($key, $json);
    }

    public function testRemove()
    {
        $key = 'sleutel';

        $cache = $this->prophesize(CacheInterface::class);
        $cache->delete($key)->shouldBeCalled();

        $jsonCache = new Msgpack($cache->reveal());
        $jsonCache->delete($key);
    }
}

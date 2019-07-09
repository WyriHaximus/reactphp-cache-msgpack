<?php declare(strict_types=1);

namespace WyriHaximus\Tests\React\Cache;

use React\Cache\CacheInterface;
use function React\Promise\resolve;
use WyriHaximus\AsyncTestUtilities\AsyncTestCase;
use WyriHaximus\React\Cache\Msgpack;

/**
 * @internal
 */
final class MsgpackTest extends AsyncTestCase
{
    public function testGet(): void
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

    public function testGetNullShouldBeIgnored(): void
    {
        $key = 'sleutel';

        $cache = $this->prophesize(CacheInterface::class);
        $cache->get($key, null)->shouldBeCalled()->willReturn(resolve(null));

        $jsonCache = new Msgpack($cache->reveal());
        self::assertNull($this->await($jsonCache->get($key)));
    }

    public function testSet(): void
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

    public function testRemove(): void
    {
        $key = 'sleutel';

        $cache = $this->prophesize(CacheInterface::class);
        $cache->delete($key)->shouldBeCalled();

        $jsonCache = new Msgpack($cache->reveal());
        $jsonCache->delete($key);
    }
}

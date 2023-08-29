<?php

declare(strict_types=1);

namespace Tests;

use Sunaoka\DefaultDict\DefaultDict;

class DefaultDictTest extends TestCase
{
    public function test_null(): void
    {
        $dd = new DefaultDict(null);

        self::assertNull($dd['x']);
    }

    public function test_int(): void
    {
        $dd = new DefaultDict(0);

        self::assertSame(0, $dd['x']);

        $dd['x']++;

        self::assertSame(1, $dd['x']);
    }

    public function test_float(): void
    {
        $dd = new DefaultDict(0.1);

        self::assertSame(0.1, $dd['x']);

        $dd['x']++;

        self::assertSame(1.1, $dd['x']);
    }

    public function test_string(): void
    {
        $dd = new DefaultDict('x');

        self::assertSame('x', $dd['x']);
    }

    public function test_closure(): void
    {
        $dd = new DefaultDict(function ($offset) {
            return $offset === 'x' ? 0 : 1;
        });

        self::assertSame(0, $dd['x']);
        self::assertSame(1, $dd['y']);
    }

    public function test_in_int_dict(): void
    {
        $dd = new DefaultDict(new DefaultDict(0));

        self::assertSame(0, $dd['x']['y']);

        $dd['x']['y']++;

        self::assertSame(1, $dd['x']['y']);
    }

    public function test_in_string_dict(): void
    {
        $dd = new DefaultDict(new DefaultDict(''));

        self::assertSame('', $dd['x']['y']);

        $dd['x']['y'] = 'string';

        self::assertSame('string', $dd['x']['y']);
    }

    public function test_is_set(): void
    {
        $dd = new DefaultDict(0);

        self::assertTrue(isset($dd['x']));

        unset($dd['x']);

        self::assertTrue(isset($dd['x']));
    }

    public function test_set(): void
    {
        $dd = new DefaultDict(0);

        $dd['x'] = 1;

        self::assertSame(1, $dd['x']);
    }

    public function test_to_array(): void
    {
        $dd = new DefaultDict(0);

        self::assertSame([], $dd->toArray());

        $dd['x'] = 1;

        self::assertSame(['x' => 1], $dd->toArray());
    }
}

<?php

declare(strict_types=1);

namespace Tests;

use IvelumTest\Helper;
use PHPUnit\Framework\TestCase;

final class HelperTest extends TestCase
{
    public function testCanBeUsedAsString(): void
    {
        self::assertEquals(
            'test 12 devdev™ but get simple™',
            (new Helper())->addSymbolToString('test 12 devdev but get simple')
        );
    }
}

<?php

declare(strict_types=1);

namespace Tests;

use IvelumTest\Request;
use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase
{
    public function testCanReturnContent(): void
    {
        self::assertGreaterThan(
            0,1,
            (new Request())->getOutsourceInfo('https://news.ycombinator.com')
        );
    }

    public function testNoResultFromRequest(): void
    {
        self::assertNotTrue(
            'No result.',
            (new Request())->getOutsourceInfo('')
        );
    }
}

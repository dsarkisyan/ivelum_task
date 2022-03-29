<?php

declare(strict_types=1);

namespace Tests;

use IvelumTest\DomParser;
use PHPUnit\Framework\TestCase;

final class DomParserTest extends TestCase
{
    public function testCanBeUsedAsString(): void
    {
        self::assertEquals(
            '<!DOCTYPE html>
<html><body><div>test 12 devdev&trade; but get simple&trade;</div></body></html>
',
            (new DomParser('news.ycombinator.com', 'https'))->changeData(
             '<!DOCTYPE html><html><body><div>test 12 devdev but get simple</div></body></html>'
            )
        );
    }

    public function testCannotBeCreatedFromInvalidEmailAddress(): void
    {
        self::assertNotTrue(
            'No result.',
            (new DomParser('news.ycombinator.com', 'https'))->changeData('')
        );
    }
}

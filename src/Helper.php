<?php

declare(strict_types=1);

namespace IvelumTest;

/**
 * Class Helper
 * @package IvelumTest
 */
class Helper
{
    /**
     * @param string $string
     * @return string
     */
    public function addSymbolToString(string $string): string
    {
        return preg_replace('/\b(\w{6})\b/i', '${1}™', $string);
    }
}

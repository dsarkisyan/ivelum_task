<?php

declare(strict_types=1);

namespace IvelumTest;

/**
 * Class Request
 * @package IvelumTest
 */
class Request
{
    /**
     * @param string $link
     * @return string
     * @example link='https://news.ycombinator.com/item?id=13713480'
     */
    public function getOutsourceInfo(string $link): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        if (!$output) {
            return 'No result.';
        }
        return $output;
    }
}

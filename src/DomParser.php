<?php

declare(strict_types=1);

namespace IvelumTest;

use DOMDocument;
use DOMElement;

/**
 * Class DomParser
 * @package IvelumTest
 */
class DomParser
{
    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var string|null
     */
    private $defaultHost;

    /**
     * DomParser constructor.
     * @param string|null $defaultHost
     * @param string|null $defaultSchema
     */
    public function __construct(?string $defaultHost = null, ?string $defaultSchema = null)
    {
        $this->defaultHost   = $defaultHost;
        $this->defaultSchema = $defaultSchema;
        $this->helper        = new Helper();
    }

    /**
     * @param string $htmlString
     * @return string
     */
    public function changeData(string $htmlString): string
    {
        if (!$htmlString) {
            return 'No result.';
        }
        $html = new DOMDocument();
        $html->loadHTML($htmlString);
        $divs = $html->getElementsByTagName('div');

        /**
         * @var DOMElement $div
         */
        foreach ($divs as $div) {
            $div->textContent = $this->helper->addSymbolToString($div->textContent);
        }

        $aTags = $html->getElementsByTagName('a');

        /**
         * @var DOMElement $aTag
         */
        foreach ($aTags as $aTag) {
            $mainUrl = $aTag->getAttribute('href');
            $urlParts = parse_url($mainUrl);

            if (!empty($urlParts['host']) && $urlParts['host'] === $this->defaultHost) {
                str_replace($this->defaultHost, 'localhost', $mainUrl);
            }
            $aTag->setAttribute('href', $mainUrl);
        }

        $imgs = $html->getElementsByTagName('img');

        /**
         * @var DOMElement $img
         */
        foreach ($imgs as $img) {
            $img->setAttribute(
                'src',
                $this->defaultSchema . '://' .$this->defaultHost . '/' . $img->getAttribute('src')
            );
        }

        $links = $html->getElementsByTagName('link');

        /**
         * @var DOMElement $link
         */
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if ($href) {
                $link->setAttribute(
                    'href',
                    $this->defaultSchema . '://' .$this->defaultHost . '/' . $href
                );
            }
        }

        return $html->saveHTML();
    }
}

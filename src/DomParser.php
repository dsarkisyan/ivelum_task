<?php

declare(strict_types=1);

namespace IvelumTest;

use DOMDocument;
use DOMElement;
use DOMText;

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
        $html = new DOMDocument('1.0', 'UTF-8');
        @$html->loadHTML(mb_convert_encoding($htmlString, 'HTML-ENTITIES', 'UTF-8'));

        $results = $html->getElementsByTagName('body');

        /**
         * @var DOMElement $result
         */
        foreach ($results as $result) {
            $this->recursiveChangeText($result);
        }

        $aTags = $html->getElementsByTagName('a');

        /**
         * @var DOMElement $aTag
         */
        foreach ($aTags as $aTag) {
            $mainUrl = $aTag->getAttribute('href');
            $urlParts = parse_url($mainUrl);

            if (!empty($urlParts['host']) && $urlParts['host'] === $this->defaultHost) {
                $mainUrl = str_replace($this->defaultSchema . '://' . $this->defaultHost, '/', $mainUrl);
            }
            $aTag->setAttribute('href', $mainUrl);
        }

        return $html->saveHTML();
    }

    /**
     * @param string $extension
     * @param string $content
     * @return string
     */
    public function insertContent(string $extension, string $content): string
    {
        $result = '';
        switch ($extension) {
            case 'css':
            case 'js':
                $result = $content ;
                break;
            case 'gif':
                header('Content-type: image/jpeg;');
                header("Content-Length: " . strlen($content));
                $result = $content;
                break;
            case 'ico':
                header('Content-type: image/x-icon;');
                header("Content-Length: " . strlen($content));
                $result = $content;
                break;
        }
        return $result;
    }

    /**
     * @param DOMElement $node
     */
    private function recursiveChangeText(DOMElement $node): void
    {
        if ($node->hasChildNodes() && (int)$node->nodeType === 1) {
            /**
             * @var DOMElement $childNode
             */
            foreach ($node->childNodes as $childNode) {
                if ($childNode instanceof DOMText) {
                    $childNode->textContent = $this->helper->addSymbolToString($childNode->textContent);
                    continue;
                }
                $this->recursiveChangeText($childNode);
            }
        } elseif (!$node->hasChildNodes() && (int)$node->nodeType === 3) {
            $node->textContent = $this->helper->addSymbolToString($node->textContent);
        }
    }
}

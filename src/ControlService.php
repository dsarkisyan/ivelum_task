<?php

declare(strict_types=1);

namespace IvelumTest;

/**
 * Class ControlService
 * @package IvelumTest
 */
class ControlService
{
    /**
     * @var string|null
     */
    private $path;

    /**
     * @var string|null
     */
    private $query;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var DomParser
     */
    private $domParser;

    /**
     * @var string
     */
    private $defaultUri;

    /**
     * @var bool
     */
    private $isFileContent;

    /**
     * @var string
     */
    private $fileExtension;
    
    private const POSSIBLE_EXTENSIONS = [
        'css' => true,
        'js'  => true,
        'gif' => true,
        'ico' => true,
    ];

    /**
     * ControlService constructor.
     * @param string|null $path
     * @param string|null $query
     */
    public function __construct(?string $path = null, ?string $query = null)
    {
        $this->defaultUri = 'https://news.ycombinator.com';
        $this->path = $path;
        $this->query = $query;
        $this->request = new Request();
        $this->domParser = new DomParser('news.ycombinator.com', 'https');
        $this->isFileContent = false;
    }

    /**
     * @return string
     */
    public function makeContentForMainApp(): string
    {
        $link = $this->parseURI();
        $res = $this->request->getOutsourceInfo($link);
        if ($this->isFileContent) {
            return $this->domParser->insertContent($this->fileExtension, $res);
        }
        return $this->domParser->changeData($res);
    }

    /**
     * @return string
     */
    private function parseURI(): string
    {
        $explodedParams = [];
        $link = '';
        if ($this->query) {
            parse_str($this->query, $explodedParams);
        }

        if (!empty($explodedParams['scheme'])) {
            $link .= $explodedParams['scheme'] . '://';
            unset($explodedParams['scheme']);
        }

        if (!empty($explodedParams['host'])) {
            $link .= $explodedParams['host'];
            unset($explodedParams['host']);
        } else {
            $link .= $this->defaultUri;
        }

        if ($this->path) {
            $link .= $this->path;
            $this->checkPathContains($this->path);
        }

        if ($explodedParams) {
            $link .= '?' . http_build_query($explodedParams);
        }

        return $link;
    }

    /**
     * @param string $haystack
     */
    private function checkPathContains(string $haystack): void
    {
        $parts = explode('.', $haystack);
        if (!empty($parts[1]) && self::POSSIBLE_EXTENSIONS[$parts[1]]) {
            $this->isFileContent = true;
            $this->fileExtension = $parts[1];
        }
    }
}

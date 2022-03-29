<?php

declare(strict_types=1);

namespace IvelumTest;

class App
{
    /**
     * @var App
     */
    private static $appInstance;

    /**
     * @var string|null
     */
    private $path;

    /**
     * @var string|null
     */
    private $query;

    /**
     * @var string
     */
    private $result;

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (!self::$appInstance instanceof self) {
            self::$appInstance = new self();
        }
        return self::$appInstance;
    }

    /**
     * App constructor.
     */
    public function __construct()
    {
        $uriParts    = parse_url($_SERVER['REQUEST_URI']);
        $this->path  = $uriParts['path'] ?? null;
        $this->query = $uriParts['query'] ?? null;
    }

    /**
     * @return self
     */
    public function runApplication(): self
    {
        $this->result = (new ControlService($this->path, $this->query))->makeContentForMainApp();
        return $this;
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }
}
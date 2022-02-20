<?php

namespace ConcreteCMS\Translate\Navigation;

use Concrete\Core\Application\Application;
use Concrete\Core\Cache\Level\ExpensiveCache;
use Concrete\Core\Http\Client\Client;
use DOMDocument;
use DOMXPath;
use RuntimeException;

defined('C5_EXECUTE') or die('Access Denied.');

class FooterContentProvider
{
    private const REMOTE_URL = 'https://community.concretecms.com';
    private const CACHE_LIFETIME = 24 * 60 * 60;

    private Application $app;

    private ExpensiveCache $cache;

    public function __construct(Application $app, ExpensiveCache $cache)
    {
        $this->app = $app;
        $this->cache = $cache;
    }

    public function getFooterContent(): string
    {
        $cacheItem = $this->cache->getItem('Footer/ContainerHtml');
        if (!$cacheItem->isMiss()) {
            return $cacheItem->get();
        }
        $footerContent = $this->fetchFooterContent();
        $this->cache->save($cacheItem->set($footerContent)->expiresAfter(self::CACHE_LIFETIME));

        return $footerContent;
    }

    private function fetchFooterContent(): string
    {
        $pageHtml = $this->fetchPageHtml();
        return $this->extractFooterContentHtml($pageHtml);
    }

    private function fetchPageHtml(): string
    {
        $client = $this->app->make(Client::class);
        $response = $client->get(self::REMOTE_URL);

        return $response->getBody()->getContents();
    }

    private function extractFooterContentHtml(string $pageHtml): string
    {
        // Disable error report for libxml to prevent loading issues because of missing html validation etc.
        $libXmlErrors = libxml_use_internal_errors(true);
        try {
            $doc = new DOMDocument();
            $doc->loadHTML($pageHtml);
        } finally {
            libxml_use_internal_errors($libXmlErrors);
        }
        $xpath = new DOMXPath($doc);
        $footerNode = $doc->getElementsByTagName('footer')->item(0);
        if ($footerNode === null) {
            throw new RuntimeException('footer element not found in ' . self::REMOTE_URL);
        }
        $footerContainerNode = $xpath->query('div/div[1]', $footerNode)->item(0);
        if ($footerContainerNode === null) {
            throw new RuntimeException('footer element retrieved from in ' . self::REMOTE_URL . " doesn't contain expected structure");
        }

        return $doc->saveHTML($footerContainerNode);
    }
}

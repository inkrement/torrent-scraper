<?php

namespace Inkrement\TorrentScraper;

use Inkrement\TorrentScraper\Adapter\TorrentProjectAdapter;
use Inkrement\TorrentScraper\Entity\SearchResult;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class TorrentProjectAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $rawResultCache;

    public function testIsImplementingAdapterInterface()
    {
        $adapter = new TorrentProjectAdapter();

        $this->assertInstanceOf('\Inkrement\TorrentScraper\AdapterInterface', $adapter);
    }

    public function testIsGettingAndSettingHttpClient()
    {
        $adapter = new TorrentProjectAdapter();

        $adapter->setHttpClient(new Client());
        $actual = $adapter->getHttpClient();

        $this->assertInstanceOf('\GuzzleHttp\Client', $actual);
    }

    public function testIsPerformingSearch()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], $this->getMockRawResult()),
        ]);
        $adapter = new TorrentProjectAdapter();

        $adapter->setHttpClient(new Client(['handler' => $mockHandler]));
        $result1 = new SearchResult();
        $result1->setName('elementaryos-0.3.2-stable-i386.20151209.iso');
        $result1->setCategory('all');
        $result1->setSize(1126170624);
        $result1->setTorrentHash('001b104e49d517cf7a41593a73c3861b7c8e34f8');
        $result1->setSeeders(15);
        $result1->setLeechers(2);

        $actual = $adapter->search('The Walking Dead S05E08')->wait();

        $this->assertEquals($result1, $actual->getSearchResult()[0]);
    }

    protected function getMockRawResult()
    {
        if (!$this->rawResultCache) {
            $this->rawResultCache = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'torrentproject_result.html');
        }

        return $this->rawResultCache;
    }

    public function testFunctional()
    {
        $adapter = new TorrentProjectAdapter();
        $adapter->setHttpClient(new Client());

        $actual = $adapter->search('Debian')->wait();

        $this->assertTrue(count($actual) > 0);
        $this->assertNotEmpty($actual[0]->getName());
        $this->assertNotNull($actual[0]->getSeeders());
        $this->assertNotNull($actual[0]->getLeechers());
        $this->assertNull($actual[0]->getTorrentUrl());
        $this->assertRegExp('/^magnet:.*$/', $actual[0]->getMagnetUrl());
    }
}

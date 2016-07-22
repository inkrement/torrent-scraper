<?php

namespace Inkrement\TorrentScraper;

class TorrentScraperServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testIsSettingAndGettingTheAdapter()
    {
        $service = new TorrentScraperService(['null']);
        $actual = $service->getAdapters();

        $this->assertInstanceOf('\Inkrement\TorrentScraper\Adapter\NullAdapter', $actual[0]);
    }

    public function testIsHttpClientBeingSet()
    {
        $adapterMock = $this->createMock('Inkrement\TorrentScraper\AdapterInterface');
        $adapterMock->expects($this->once())
            ->method('setHttpClient')
            ->with(new \GuzzleHttp\Client());

        $service = new TorrentScraperService([]);

        $service->addAdapter($adapterMock);
    }
}

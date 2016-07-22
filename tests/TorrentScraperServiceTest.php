<?php

namespace Inkrement\TorrentScraper;

use Inkrement\TorrentScraper\Entity\SearchResult;

class TorrentScraperServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testIsSettingAndGettingTheAdapter()
    {
        $service = new TorrentScraperService(['null']);
        $actual = $service->getAdapters();

        $this->assertInstanceOf('\Inkrement\TorrentScraper\Adapter\NullAdapter', $actual[0]);
    }

    public function testIsSearchingInTheAdapters()
    {
        $expected = [new SearchResult()];
        $adapter1 = $this->getMock('Inkrement\TorrentScraper\AdapterInterface');
        $adapter1->expects($this->once())
            ->method('search')
            ->with('The Walking Dead S05E08')
            ->willReturn($expected);

        $adapter2 = $this->getMock('Inkrement\TorrentScraper\AdapterInterface');
        $adapter2->expects($this->once())
            ->method('search')
            ->with('The Walking Dead S05E08')
            ->willReturn([]);

        $service = new TorrentScraperService([]);
        $service->addAdapter($adapter1);
        $service->addAdapter($adapter2);

        $actual = $service->search('The Walking Dead S05E08');

        $this->assertSame($expected, $actual);
    }

    public function testIsHttpClientBeingSet()
    {
        $adapterMock = $this->getMock('Inkrement\TorrentScraper\AdapterInterface');
        $adapterMock->expects($this->once())
            ->method('setHttpClient')
            ->with(new \GuzzleHttp\Client());

        $service = new TorrentScraperService([]);

        $service->addAdapter($adapterMock);
    }
}

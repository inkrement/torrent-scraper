<?php

namespace Inkrement\TorrentScraper;

use Inkrement\TorrentScraper\Adapter\ThePirateBayAdapter;
use Inkrement\TorrentScraper\Entity\SearchResult;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ThePirateBayAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $rawResultCache;

    public function testIsImplementingAdapterInterface()
    {
        $adapter = new ThePirateBayAdapter();

        $this->assertInstanceOf('\Inkrement\TorrentScraper\AdapterInterface', $adapter);
    }

    public function testIsGettingAndSettingHttpClient()
    {
        $adapter = new ThePirateBayAdapter();

        $adapter->setHttpClient(new Client());
        $actual = $adapter->getHttpClient();

        $this->assertInstanceOf('\GuzzleHttp\Client', $actual);
    }

    public function testIsPerformingSearch()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], $this->getMockRawResult()),
        ]);
        $adapter = new ThePirateBayAdapter();

        $adapter->setHttpClient(new Client(['handler' => $mockHandler]));
        $result1 = new SearchResult();
        $result1->setName('ElementaryOs Freya AMD64');
        $result1->setCategory('Applications > UNIX');
        $result1->setDate(\DateTime::createFromFormat('Y-m-d h:m', '2016-01-04 00:00'));
        $result1->setSize(1148903751);
        $result1->setUploader(null);
        $result1->setSeeders(3);
        $result1->setLeechers(2);
        $result1->setTorrentUrl('/torrent/13133483/ElementaryOs_Freya_AMD64');
        $result1->setMagnetUrl('magnet:?xt=urn:btih:fce720af722a813a184c5550a924aaa60a8d9af1&dn=ElementaryOs+Freya+AMD64&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Fzer0day.ch%3A1337&tr=udp%3A%2F%2Fopen.demonii.com%3A1337&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');

        $result2 = new SearchResult();
        $result2->setName('elementaryos luna 04 September 11');
        $result2->setCategory('Applications > UNIX');
        $result2->setDate(\DateTime::createFromFormat('Y-m-d h:m', '2011-09-04 00:00'));
        $result2->setSize(846871920);
        $result2->setUploader('alfalive');
        $result2->setSeeders(0);
        $result2->setLeechers(0);
        $result2->setTorrentUrl('/torrent/6650951/elementaryos_luna_04_September_11');
        $result2->setMagnetUrl('magnet:?xt=urn:btih:8ae1883db5ed9fe74cacfebd2e06483dd4434e53&dn=elementaryos+luna+04+September+11&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Fzer0day.ch%3A1337&tr=udp%3A%2F%2Fopen.demonii.com%3A1337&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');

        $result3 = new SearchResult();
        $result3->setName('elementaryos-beta2-i386.20130506.iso');
        $result3->setCategory('Applications > UNIX');
        $result3->setDate(\DateTime::createFromFormat('Y-m-d h:m', '2013-05-15 00:00'));
        $result3->setSize(679477248);
        $result3->setUploader('ixcoder');
        $result3->setSeeders(0);
        $result3->setLeechers(0);
        $result3->setTorrentUrl('/torrent/8477045/elementaryos-beta2-i386.20130506.iso');
        $result3->setMagnetUrl('magnet:?xt=urn:btih:94cb5bf6784f9596c56164251d4e5bd76b3a07a2&dn=elementaryos-beta2-i386.20130506.iso&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Fzer0day.ch%3A1337&tr=udp%3A%2F%2Fopen.demonii.com%3A1337&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');

        $result4 = new SearchResult();
        $result4->setName('elementaryos-beta2-amd64.20130506.iso');
        $result4->setCategory('Applications > UNIX');
        $result4->setDate(\DateTime::createFromFormat('Y-m-d h:m', '2013-05-15 00:00'));
        $result4->setSize(713031680);
        $result4->setUploader('ixcoder');
        $result4->setSeeders(0);
        $result4->setLeechers(0);
        $result4->setTorrentUrl('/torrent/8477082/elementaryos-beta2-amd64.20130506.iso');
        $result4->setMagnetUrl('magnet:?xt=urn:btih:ac86fca020c96066862da1b5fcdf967e2622528d&dn=elementaryos-beta2-amd64.20130506.iso&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Fzer0day.ch%3A1337&tr=udp%3A%2F%2Fopen.demonii.com%3A1337&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');

        $result5 = new SearchResult();
        $result5->setName('ElementaryOS 64-bit 20130810');
        $result5->setCategory('Applications > UNIX');
        $result5->setDate(\DateTime::createFromFormat('Y-m-d h:m', '2013-11-01 00:00'));
        $result5->setSize(727711744);
        $result5->setUploader(null);
        $result5->setSeeders(0);
        $result5->setLeechers(0);
        $result5->setTorrentUrl('/torrent/9129553/ElementaryOS_64-bit_20130810');
        $result5->setMagnetUrl('magnet:?xt=urn:btih:daaf19e0989a9f730ee4c2d97bd257c71a5b83f0&dn=ElementaryOS+64-bit+20130810&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Fzer0day.ch%3A1337&tr=udp%3A%2F%2Fopen.demonii.com%3A1337&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');

        $result6 = new SearchResult();
        $result6->setName('ElementaryOS 32-bit 20130810');
        $result6->setCategory('Applications > UNIX');
        $result6->setDate(\DateTime::createFromFormat('Y-m-d h:m', '2013-12-23 00:00'));
        $result6->setSize(694157312);
        $result6->setUploader(null);
        $result6->setSeeders(0);
        $result6->setLeechers(0);
        $result6->setTorrentUrl('/torrent/9385861/ElementaryOS_32-bit_20130810');
        $result6->setMagnetUrl('magnet:?xt=urn:btih:d9f6366a03a4d895865e52d5ca67dd15f7250b37&dn=ElementaryOS+32-bit+20130810&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Fzer0day.ch%3A1337&tr=udp%3A%2F%2Fopen.demonii.com%3A1337&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');

        $expected = [$result1, $result2, $result3, $result4, $result5, $result6];

        $actual = $adapter->search('The Walking Dead S05E08')->wait();

        $this->assertEquals($expected, $actual->getSearchResult());
    }

    public function testParseDescription()
    {
        $input1 = 'Enviado 05-15 2013, Tamanho de 680 MiB, UP por ';
        $input2 = 'Enviado 11-01 2013, Tamanho de 694 MiB, UP por';
        $input3 = 'Enviado 10-02 11:12, Tamanho de 720 MiB, UP por';

        $result1 = ThePirateBayAdapter::parseDescription($input1);
        $result2 = ThePirateBayAdapter::parseDescription($input2);
        $result3 = ThePirateBayAdapter::parseDescription($input3);

        $this->assertEquals(['date' => new \DateTime('2013-05-15'), 'bytes' => 713031680], $result1);
        $this->assertEquals(['date' => new \DateTime('2013-11-01'), 'bytes' => 727711744], $result2);
        $this->assertEquals(['date' => new \DateTime('2016-10-02'), 'bytes' => 754974720], $result3);
    }

    protected function getMockRawResult()
    {
        if (!$this->rawResultCache) {
            $this->rawResultCache = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'the_pirate_bay_result.html');
        }

        return $this->rawResultCache;
    }

    public function testFunctional()
    {
        $adapter = new ThePirateBayAdapter();
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

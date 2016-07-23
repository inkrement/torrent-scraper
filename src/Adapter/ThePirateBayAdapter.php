<?php

namespace Inkrement\TorrentScraper\Adapter;

use Inkrement\TorrentScraper\AdapterInterface;
use Inkrement\TorrentScraper\HttpClientAware;
use Inkrement\TorrentScraper\Entity\SearchResult;
use Inkrement\TorrentScraper\Entity\TrackerResponse;
use Symfony\Component\DomCrawler\Crawler;

class ThePirateBayAdapter implements AdapterInterface
{
    use HttpClientAware;

    private $options;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @param string $query
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function search($query)
    {
        $url = 'https://thepiratebay.se/search/'.urlencode($query).'/0/7/0';

        return $this->httpClient->requestAsync('GET', $url, $this->options)->then(function ($response) {
          return (string) $response->getBody();
        })->then(function ($htmlBody) {
          $response = new TrackerResponse();
          $response->setTracker('thePirateBay');
          $response->setSearchResult(Self::parseResponse($htmlBody));
          $response->setQuery($query);

        return $response;
        });
    }

    /**
     * Parses thepiratebay html response.
     *
     * @param string $htmlBody
     *
     * @return SearchResult[]
     */
    private static function parseResponse($htmlBody)
    {
        $crawler = new Crawler($htmlBody);
        $items = $crawler->filter('#searchResult tr');
        $results = [];
        $first = true;

        foreach ($items as $item) {
            // Ignore the first row, the header
          if ($first) {
              $first = false;
              continue;
          }

            $result = new SearchResult();
            $itemCrawler = new Crawler($item);
            $result->setName(trim($itemCrawler->filter('.detName')->text()));
            $result->setSeeders((int) $itemCrawler->filter('td')->eq(2)->text());
            $result->setLeechers((int) $itemCrawler->filter('td')->eq(3)->text());
            $result->setMagnetUrl($itemCrawler->filterXpath('//tr/td/a')->attr('href'));

            $results[] = $result;
        }

        return $results;
    }
}

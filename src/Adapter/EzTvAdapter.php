<?php

namespace Inkrement\TorrentScraper\Adapter;

use Inkrement\TorrentScraper\AdapterInterface;
use Inkrement\TorrentScraper\HttpClientAware;
use Inkrement\TorrentScraper\Entity\SearchResult;
use Inkrement\TorrentScraper\Entity\TrackerResponse;
use Symfony\Component\DomCrawler\Crawler;

class EzTvAdapter implements AdapterInterface
{
    use HttpClientAware;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param $options array
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
        $url = 'https://eztv.ag/search/'.$this->transformSearchString($query);

        return $this->httpClient->requestAsync('GET', $url, $this->options)->then(function ($response) {
          return (string) $response->getBody();
        })->then(function ($htmlBody) use ($query) {
            $response = new TrackerResponse();
            $response->setTracker('EzTv');
            $response->setSearchResult(Self::parseResponse($htmlBody));
            $response->setQuery($query);

          return $response;
        });
    }

    /**
     * Parses eztv html response.
     *
     * @param string $htmlBody
     *
     * @return SearchResult[]
     */
    private static function parseResponse($htmlBody)
    {
        $crawler = new Crawler($htmlBody);
        $items = $crawler->filter('tr.forum_header_border');
        $results = [];

        foreach ($items as $item) {
            $result = new SearchResult();
            $itemCrawler = new Crawler($item);
            $result->setName(trim($itemCrawler->filter('td')->eq(1)->text()));
            //$result->setSeeders($this->options['seeders']);
            //$result->setLeechers($this->options['leechers']);

            $node = $itemCrawler->filter('a.download_1');
            if ($node->count() > 0) {
                $result->setTorrentUrl($node->eq(0)->attr('href'));
            }

            $node = $itemCrawler->filter('a.magnet');
            if ($node->count() > 0) {
                $result->setMagnetUrl($node->eq(0)->attr('href'));
            }

            $results[] = $result;
        }

        return $results;
    }

    /**
     * Transform every non alphanumeric character into a dash.
     *
     * @param string $searchString
     *
     * @return mixed
     */
    public function transformSearchString($searchString)
    {
        return preg_replace('/[^a-z0-9]/', '-', strtolower($searchString));
    }
}

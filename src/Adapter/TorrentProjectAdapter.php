<?php

namespace Inkrement\TorrentScraper\Adapter;

use Inkrement\TorrentScraper\AdapterInterface;
use Inkrement\TorrentScraper\HttpClientAware;
use Inkrement\TorrentScraper\Entity\SearchResult;
use Inkrement\TorrentScraper\Entity\TrackerResponse;
use Psr\Http\Message\ResponseInterface;

class TorrentProjectAdapter implements AdapterInterface
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
        $url = 'https://torrentproject.se/?s='.urlencode($query).'&out=json';

        return $this->httpClient->requestAsync('GET', $url, $this->options)->then(function (ResponseInterface $response) {
          return (string) $response->getBody();
        })->then(function ($htmlBody) use ($query) {
          $response = new TrackerResponse();
          $response->setTracker('torrentproject');
          $response->setSearchResult(self::parseResponse($htmlBody));
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

        $results = [];
        $raw_results = json_decode($htmlBody, true);
        
        foreach ($raw_results as $row) {
            if(is_array($row)){
                $result = new SearchResult();

                $result->setTorrentHash($row['torrent_hash']);
                $result->setName($row['title']);
                $result->setCategory($row['category']);
                $result->setLeechers($row['leechs']);
                $result->setSeeders($row['seeds']);
                $result->setSize($row['torrent_size']);

                $results[] = $result;
            }

        }

        return $results;
    }
}

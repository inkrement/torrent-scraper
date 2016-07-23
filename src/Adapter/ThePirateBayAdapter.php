<?php

namespace Inkrement\TorrentScraper\Adapter;

use Inkrement\TorrentScraper\AdapterInterface;
use Inkrement\TorrentScraper\HttpClientAware;
use Inkrement\TorrentScraper\Entity\SearchResult;
use Inkrement\TorrentScraper\Entity\TrackerResponse;
use Symfony\Component\DomCrawler\Crawler;

class ThePirateBayAdapter implements AdapterInterface
{
    const INFO_REGEX = '/([0-1][0-9])-([0-3][0-9])(?:&nbsp;| )?([0-9]{4})?([0-2][0-9]:[0-6][0-9])?,[^0-9]*([0-9.]*(?:&nbsp;| )[a-zA-Z]{1,3})/';
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
        })->then(function ($htmlBody) use ($query) {
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
            $result->setCategory($itemCrawler->filter('.vertTh a')->first()->text());

            try {
                $result->setUploader($itemCrawler->filterXpath('//font[@class="detDesc"]/a')->text());
            } catch (\InvalidArgumentException $e) {
            }

            $raw = Self::clean($itemCrawler->filterXpath('//font[@class="detDesc"]')->text());

            preg_match(Self::INFO_REGEX, $raw, $matches);

            if (count($matches) > 2) {
                $year = (isset($matches[4])) ? $matches[3] : '2016';

                if (!empty($matches[1]) && !empty($matches[2])) {
                    $result->setDate(new \DateTime($year.'-'.$matches[1].'-'.$matches[2]));
                }
            }

            if (isset($matches[5])) {
                $result->setSize((int) rtrim(\ByteUnits\parse($matches[5])->format('B'), 'B'));
            }

            $result->setSeeders((int) $itemCrawler->filter('td')->eq(2)->text());
            $result->setLeechers((int) $itemCrawler->filter('td')->eq(3)->text());
            $result->setMagnetUrl($itemCrawler->filterXpath('//tr/td/a')->attr('href'));

            $results[] = $result;
        }

        return $results;
    }

    private static function clean($str)
    {
        $str = utf8_decode($str);
        $str = str_replace('&nbsp;', '', $str);
        $str = preg_replace("/\s+/", ' ', $str);
        $str = trim($str);

        return $str;
    }
}

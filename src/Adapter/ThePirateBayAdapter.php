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
        $header = true;

        foreach ($items as $item) {
            if ($header) {
                $header = false;
                continue;
            }
            $result = new SearchResult();
            $itemCrawler = new Crawler($item);

            try {
              $row = $itemCrawler->filter('td');

              $result->setCategory($row->eq(0)->text());
              $result->setName(trim($row->eq(1)->text()));
              $result->setTorrentUrl($row->eq(1)->filter('a')->attr('href'));

              //Hack: replace time with current year. So the test will fail next year
              $date_raw = Self::clean($row->eq(2)->text());
              $date_raw = (strpos($date_raw, ':') !== false) ? substr($date_raw, 0, 6).date('Y') : $date_raw;
              $datetime = \DateTime::createFromFormat('m-d Y h:m', $date_raw.' 00:00');
              if ($datetime !== false) {
                  $result->setDate($datetime);
              }

              $result->setMagnetUrl($row->eq(3)->filter('a')->attr('href'));

              $size_raw = Self::clean($row->eq(4)->text());
              $result->setSize((int) rtrim(\ByteUnits\parse($size_raw)->format('B'), 'B'));

              $result->setSeeders((int) $row->eq(5)->text());
              $result->setLeechers((int) $row->eq(6)->text());
              $result->setUploader($row->eq(7)->filter('a')->text());

            } catch(\InvalidArgumentException $e){
            }

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

    public static function bytes($bytes, $force_unit = null, $format = null, $si = true)
    {
        // Format string
    $format = ($format === null) ? '%01.2f %s' : (string) $format;

    // IEC prefixes (binary)
    if ($si == false or strpos($force_unit, 'i') !== false) {
        $units = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
        $mod = 1024;
    }
    // SI prefixes (decimal)
    else {
        $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
        $mod = 1000;
    }

    // Determine unit to use
    if (($power = array_search((string) $force_unit, $units)) === false) {
        $power = ($bytes > 0) ? floor(log($bytes, $mod)) : 0;
    }

        return sprintf($format, $bytes / pow($mod, $power), $units[$power]);
    }
}

Torrent Scraper
===============
This library is based on [Fernando Carlétti`s](https://github.com/xurumelous/torrent-scraper) torrent-scraper. It provides an abstraction to search for torrent files across some torrent websites.

## Usage

First you have to install it using composer:

```shell
composer require inkrement/torrent-scraper
```


```php
<?php

require 'vendor/autoload.php';
use Inkrement\TorrentScraper\TorrentScraperService;

date_default_timezone_set('UTC');

$scraperService = new TorrentScraperService(['ezTv', 'ThePirateBay']);
$results = $scraperService->search('elementaryos');

foreach($tracker as $tracker_results){
  echo $tracker_results->getTracker()."\n";

  foreach ($tracker_results->getSearchResult() as $result) {
      $result->getName();
      $result->getSeeders();
      $result->getLeechers();
      $result->getTorrentUrl();
      $result->getMagnetUrl();
  }
}


```


### Proxy
You can pass *Guzzle httpClient* options directly to the adapters.

```php
$scraperService = new TorrentScraperService();

//add adapter
$pirateBayAdapter = new ThePirateBayAdapter(['proxy' => 'http://username:password@example.com:3128']);
$scraperService->addAdapter($pirateBayAdapter);

$result = $scraperService->search('elementaryos');
```

## Available adapters
* [ezTv](https://eztv.ag/)
* [thePirateBay](http://thepiratebay.se)

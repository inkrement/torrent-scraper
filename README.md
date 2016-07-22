Torrent Scraper
===============

## About
This library provides an abstraction to search for torrent files accross some torrent websites.

## Usage
```php
<?php

require 'vendor/autoload.php';
use Inkrement\TorrentScraper\TorrentScraperService;

date_default_timezone_set('UTC');

$scraperService = new TorrentScraperService(['ezTv', 'ThePirateBay']);
$results = $scraperService->search('elementaryos');

foreach ($results as $result) {
    $result->getName();
    $result->getSeeders();
    $result->getLeechers();
    $result->getTorrentUrl();
    $result->getMagnetUrl();
}
```

## Available adapters
* [ezTv](https://eztv.ag/)
* [thePirateBay](http://thepiratebay.se)

Torrent Scraper
===============
This library is based on [Fernando Carlétti`s](https://github.com/xurumelous/torrent-scraper) torrent-scraper.

## About
This library provides an abstraction to search for torrent files across some torrent websites.

## Usage

First you have to install it using composer:
```bash
composer require inkrement/torrent-scraper
```


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

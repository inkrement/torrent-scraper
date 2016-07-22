<?php

namespace Inkrement\TorrentScraper;

use Inkrement\TorrentScraper\Entity\SearchResult;
use GuzzleHttp\Promise\EachPromise;

class TorrentScraperService
{
    /**
     * @var AdapterInterface[]
     */
    protected $adapters;

    /**
     * @param array $adapters
     * @param array $options
     */
    public function __construct(array $adapters, $options = [])
    {
        foreach ($adapters as $adapter) {
            $adapterName = __NAMESPACE__.'\\Adapter\\'.ucfirst($adapter).'Adapter';
            $this->addAdapter(new $adapterName($options));
        }
    }

    /**
     * @param AdapterInterface $adapter
     */
    public function addAdapter(AdapterInterface $adapter)
    {
        if (!$adapter->getHttpClient()) {
            $adapter->setHttpClient(new \GuzzleHttp\Client());
        }

        $this->adapters[] = $adapter;
    }

    /**
     * @return AdapterInterface[]
     */
    public function getAdapters()
    {
        return $this->adapters;
    }

    /**
     * @param string $query
     *
     * @return SearchResult[]
     */
    public function search($query)
    {
        $promises = [];

        foreach ($this->adapters as $adapter) {
            $promises[] = $adapter->search($query);
        }

        $each = new EachPromise($promises, [
          'concurrency' => 4,
          'fulfilled' => function ($responses) {
               return $responses;
          },
        ]);

        $each->promise()->wait();
    }
}

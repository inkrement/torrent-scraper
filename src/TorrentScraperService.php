<?php

namespace Inkrement\TorrentScraper;

use Inkrement\TorrentScraper\Entity\SearchResult;

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
    public function __construct(array $adapters = [], $options = [])
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
      * @param $string query
      *
      * @return \GuzzleHttp\Promise\PromiseInterface
      */
     public function asyncSearch($query)
     {
         $promises = [];

         foreach ($this->adapters as $adapter) {
             $promises[] = $adapter->search($query);
         }

         return \GuzzleHttp\Promise\all($promises);
     }

    /**
     * @param string $query
     *
     * @return SearchResult[]
     */
    public function search($query)
    {
        return $this->asyncSearch($query)->wait();
    }
}

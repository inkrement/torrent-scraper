<?php

namespace Inkrement\TorrentScraper;

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
     public function search($query)
     {
         foreach ($this->adapters as $adapter) {
             yield $adapter->search($query);
         }
     }

     /**
      * @param $string query
      *
      * @return \GuzzleHttp\Promise\PromiseInterface
      */
     public function searchArray(array $queries)
     {
         foreach ($queries as $query) {
             foreach ($this->adapters as $adapter) {
                 yield $adapter->search($query);
             }
         }
     }
}

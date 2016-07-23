<?php

namespace Inkrement\TorrentScraper\Entity;

class TrackerResponse
{
    /**
     * @var string
     */
    protected $searchResult;

    /**
     * @var string
     */
    protected $query;

    /**
     * @var string
     */
    protected $tracker;

    /**
     * @param string $searchResult
     */
    public function setSearchResult($searchResult)
    {
        $this->searchResult = $searchResult;
    }

    /**
     * @return string
     */
    public function getSearchResult()
    {
        return $this->searchResult;
    }

    /**
     * @param string $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $tracker
     */
    public function setTracker($tracker)
    {
        $this->tracker = $tracker;
    }

    /**
     * @return string
     */
    public function getTracker()
    {
        return $this->tracker;
    }
}

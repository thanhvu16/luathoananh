<?php
/**
 * Copyright 2011 Bas de Nooijer. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this listof conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are
 * those of the authors and should not be interpreted as representing official
 * policies, either expressed or implied, of the copyright holder.
 *
 * @copyright Copyright 2011 Bas de Nooijer <solarium@raspberry.nl>
 * @license http://github.com/basdenooijer/solarium/raw/master/COPYING
 * @link http://www.solarium-project.org/
 */

/**
 * @namespace
 */
namespace Solarium\QueryType\Suggester\Result;

use Solarium\Core\Query\Result\QueryType as BaseResult;

/**
 * Suggester query result
 */
class Result extends BaseResult implements \IteratorAggregate, \Countable
{
    /**
     * Status code returned by Solr
     *
     * @var int
     */
    protected $status;

    /**
     * Solr index queryTime
     *
     * This doesn't include things like the HTTP responsetime. Purely the Solr
     * query execution time.
     *
     * @var int
     */
    protected $queryTime;

    /**
     * Suggester results
     *
     * @var array
     */
    protected $results;

    /**
     * Suggester flat results
     *
     * @var array
     */
    protected $all;

    /**
     * Collation result
     *
     * Only available when collate is enabled in the suggester query
     *
     * @var string
     */
    protected $collation;

    /**
     * Get Solr status code
     *
     * This is not the HTTP status code! The normal value for success is 0.
     *
     * @return int
     */
    public function getStatus()
    {
        $this->parseResponse();

        return $this->status;
    }

    /**
     * Get Solr query time
     *
     * This doesn't include things like the HTTP responsetime. Purely the Solr
     * query execution time.
     *
     * @return int
     */
    public function getQueryTime()
    {
        $this->parseResponse();

        return $this->queryTime;
    }

    /**
     * Get all results
     *
     * @return array
     */
    public function getResults()
    {
        $this->parseResponse();

        return $this->results;
    }

    /**
     * Get flat results
     *
     * @return array
     */
    public function getAll()
    {
        $this->parseResponse();

        return $this->all;
    }

    /**
     * Get results for a specific term
     *
     * @param  string $term
     * @return array
     */
    public function getTerm($term)
    {
        $this->parseResponse();

        if (isset($this->results[$term])) {
            return $this->results[$term];
        } else {
            return array();
        }
    }

    /**
     * IteratorAggregate implementation
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        $this->parseResponse();

        return new \ArrayIterator($this->results);
    }

    /**
     * Countable implementation
     *
     * @return int
     */
    public function count()
    {
        $this->parseResponse();

        return count($this->results);
    }

    /**
     * Get collation
     *
     * @return null|string
     */
    public function getCollation()
    {
        $this->parseResponse();

        return $this->collation;
    }
}

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
namespace Solarium\QueryType\Select\Result\Facet\Pivot;

use Solarium\QueryType\Select\Result\Stats\Stats;

/**
 * Select field pivot result
 *
 */
class PivotItem extends Pivot
{
    /**
     * Field name
     *
     * @var string
     */
    protected $field;

    /**
     * Field value
     *
     * @var mixed
     */
    protected $value;

    /**
     * Count
     *
     * @var int
     */
    protected $count;

    /**
     * Field stats
     *
     * @var mixed
     */
    protected $stats;

    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->field = $data['field'];
        $this->value = $data['value'];
        $this->count = $data['count'];

        if (isset($data['pivot'])) {
            foreach ($data['pivot'] as $pivotData) {
                $this->pivot[] = new PivotItem($pivotData);
            }
        }

        if (isset($data['stats'])) {
            $this->stats = new Stats($data['stats']);
        }
    }

    /**
     * Get field name
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Get field value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get count
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Get stats
     * 
     * @return Stats
     */
    public function getStats()
    {
        return $this->stats;
    }
}

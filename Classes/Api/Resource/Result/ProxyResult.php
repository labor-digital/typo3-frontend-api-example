<?php
/*
 * Copyright 2021 LABOR.digital
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Last modified: 2021.06.02 at 20:27
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Api\Resource\Result;


use LaborDigital\T3ba\Core\Di\NoDiInterface;
use LaborDigital\T3fa\Core\Resource\Repository\Pagination\SelfPaginatingInterface;

// Note: We use NoDiInterface because we are 100% sure, that this object will never be
// instantiated via the Symfony DI container. So it does not need any auto-wiring or definition of this class.
class ProxyResult implements SelfPaginatingInterface, NoDiInterface
{
    
    /**
     * A list of data you would normally retrieve through an api endpoint
     */
    public const DUMMY_DATA
        = [
            1 => [
                'title' => 'Hello World',
            ],
            2 => [
                'title' => 'Hello Sun',
            ],
            3 => [
                'title' => 'Hello Moon',
            ],
            4 => [
                'title' => 'Hello Mars',
            ],
            5 => [
                'title' => 'Hello Venus',
            ],
            6 => [
                'title' => 'Hello Mercury',
            ],
            7 => [
                'title' => 'Hello Jupiter',
            ],
            8 => [
                'title' => 'Hello Saturn',
            ],
            9 => [
                'title' => 'Hello Neptune',
            ],
            10 => [
                'title' => 'Hello Pluto, (yes, I greet you, too!)',
            ],
        ];
    
    /**
     * @var string
     */
    protected $filter;
    
    public function __construct(string $filter)
    {
        $this->filter = $filter;
    }
    
    /**
     * @inheritDoc
     */
    public function getItemsFor(int $offset, int $limit): iterable
    {
        return $this->makeApiRequest($this->filter, $offset, $limit);
    }
    
    /**
     * @inheritDoc
     */
    public function getItemCount(): int
    {
        return count($this->makeApiRequest($this->filter, 0, 0));
    }
    
    /**
     * This method simulates a request through guzzle or a similar http client
     *
     * @param   string  $filter
     * @param   int     $offset
     * @param   int     $limit
     *
     * @return array
     */
    protected function makeApiRequest(string $filter, int $offset, int $limit): array
    {
        $results = [];
        
        foreach (static::DUMMY_DATA as $id => $entry) {
            if ($filter === '' || stripos($entry['title'], $filter) !== false) {
                $results[$id] = $entry;
            }
        }
        
        if ($offset === 0 && $limit === 0) {
            return $results;
        }
        
        return array_slice($results, $offset, $limit, true);
    }
}
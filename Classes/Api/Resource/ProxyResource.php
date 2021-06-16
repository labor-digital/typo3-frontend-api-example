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
 * Last modified: 2021.06.02 at 20:35
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Api\Resource;


use LaborDigital\T3ba\Core\Di\ContainerAwareTrait;
use LaborDigital\T3ba\ExtConfig\SiteBased\SiteConfigContext;
use LaborDigital\T3fa\Core\Resource\Exception\ResourceNotFoundException;
use LaborDigital\T3fa\Core\Resource\Query\ResourceQuery;
use LaborDigital\T3fa\Core\Resource\Repository\Context\ResourceCollectionContext;
use LaborDigital\T3fa\Core\Resource\Repository\Context\ResourceContext;
use LaborDigital\T3fa\Core\Resource\ResourceInterface;
use LaborDigital\T3fa\ExtConfigHandler\Api\Resource\ResourceConfigurator;
use LaborDigital\T3faExample\Api\Resource\Result\ProxyResult;

class ProxyResource implements ResourceInterface
{
    use ContainerAwareTrait;
    
    /**
     * @inheritDoc
     */
    public static function configure(ResourceConfigurator $configurator, SiteConfigContext $context): void
    {
        $configurator->setPageSize(2);
    }
    
    /**
     * @inheritDoc
     */
    public function findSingle($id, ResourceContext $context)
    {
        // This would normally an api request,
        // for simplicity we just access the dummy data on the result object directly.
        if (isset(ProxyResult::DUMMY_DATA[$id])) {
            return ProxyResult::DUMMY_DATA[$id];
        }
        
        throw new ResourceNotFoundException();
    }
    
    /**
     * @inheritDoc
     */
    public function findCollection(ResourceQuery $resourceQuery, ResourceCollectionContext $context)
    {
        // Whenever you work with external apis to retrieve data and only proxy them through the resource api,
        // you probably don't want to retrieve all possible results to paginate them correctly.
        
        // This is where the "SelfPaginatingInterface" comes in handy. It allows your result to handle the
        // slicing of the result set internally. This allows you to only query the data from the external
        // api you need based on the resource api request.
        
        // To simulate additional filtering, we pass the "title" filter through to the result object.
        // Again, normally you would pass the filter along to a http client like guzzle or something similar.
        return $this->makeInstance(
            ProxyResult::class,
            [$resourceQuery->getFilterValue('title', '')]
        );
    }
    
}
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
 * Last modified: 2021.06.25 at 20:44
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Api\Resource;


use LaborDigital\T3ba\ExtConfig\SiteBased\SiteConfigContext;
use LaborDigital\T3fa\Core\Resource\Query\ResourceQuery;
use LaborDigital\T3fa\Core\Resource\Repository\Context\ResourceCollectionContext;
use LaborDigital\T3fa\Core\Resource\Repository\Context\ResourceContext;
use LaborDigital\T3fa\Core\Resource\ResourceInterface;
use LaborDigital\T3fa\ExtConfigHandler\Api\Resource\ResourceConfigurator;
use LaborDigital\T3faExample\Domain\Model\Faq\FaqList;
use LaborDigital\T3faExample\Domain\Repository\Faq\FaqListRepository;

class FaqListResource implements ResourceInterface
{
    /**
     * @var \LaborDigital\T3faExample\Domain\Repository\Faq\FaqListRepository
     */
    protected $repository;
    
    public function __construct(FaqListRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @inheritDoc
     */
    public static function configure(ResourceConfigurator $configurator, SiteConfigContext $context): void
    {
        $configurator->registerClass(FaqList::class);
    }
    
    /**
     * @inheritDoc
     */
    public function findSingle($id, ResourceContext $context)
    {
        return $this->repository->getQuery()->withWhere(['uid' => $id]);
    }
    
    /**
     * @inheritDoc
     */
    public function findCollection(ResourceQuery $resourceQuery, ResourceCollectionContext $context)
    {
        return $this->repository->getQuery()->getAll();
    }
    
}
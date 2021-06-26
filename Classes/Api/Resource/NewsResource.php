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
 * Last modified: 2021.06.24 at 18:46
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Api\Resource;


use LaborDigital\T3ba\ExtConfig\SiteBased\SiteConfigContext;
use LaborDigital\T3fa\Core\Resource\Query\ResourceQuery;
use LaborDigital\T3fa\Core\Resource\Repository\Context\ResourceCollectionContext;
use LaborDigital\T3fa\Core\Resource\Repository\Context\ResourceContext;
use LaborDigital\T3fa\Core\Resource\ResourceInterface;
use LaborDigital\T3fa\ExtConfigHandler\Api\Resource\ResourceConfigurator;
use LaborDigital\T3faExample\Api\Resource\PostProcessor\NewsPostProcessor;
use LaborDigital\T3faExample\Domain\Model\News;
use LaborDigital\T3faExample\Domain\Repository\NewsRepository;

class NewsResource implements ResourceInterface
{
    /**
     * @var \LaborDigital\T3faExample\Domain\Repository\NewsRepository
     */
    protected $repository;
    
    public function __construct(NewsRepository $repository)
    {
        // Resource classes support dependency injection using the symfony container
        $this->repository = $repository;
    }
    
    /**
     * @inheritDoc
     */
    public static function configure(ResourceConfigurator $configurator, SiteConfigContext $context): void
    {
        // A resource is something quite abstract, it is a contract about the type of data to be returned.
        // However which source this data has is quite dynamic, you can either use an extbase model,
        // an external api call or generate it in a completely different way.
        
        // That's why you have to map your objects that should be handled by your resource manually.
        // This resource should be all about news, so we map our news model to be linked to this resource.
        // With the mapping in place the transformation can infer the correct resource type whenever an instance
        // of the linked classes is encountered.
        $configurator->registerClass(News::class);
        
        // By default T3FA takes care of the transformation of your ext base entities into json objects.
        // But you can also modify the built result by applying post processors. Those are executed for each
        // transformed resource and are a simple way of altering the data on generation.
        // In this example we want to add the link to each news into the record.
        $configurator->registerPostProcessor(NewsPostProcessor::class);
    }
    
    /**
     * @inheritDoc
     */
    public function findSingle($id, ResourceContext $context)
    {
        // When working with resources you have two main players,
        // first is the "resource" itself. A frontend api resource is NOT to be confused with the
        // "resource" as defined by TYPO3 (which means an asset or image with that vocable).
        // The term is instead inherited from the json api "resource" object (https://jsonapi.org/format/#document-resource-objects)
        // that defines a set of data that has a specific structure and is uniquely identifiable by its (resource)type.
        
        // The "findSingle" method receives a unique id for the requested resource (either a string or an integer)
        // and should query the concrete repository in order to find the matching entry.
        
        // Pro-tip: Most objects should work out of the box here as long as they have a getter method that is follows
        // the default naming schema of property -> getProperty() or isProperty().
        
        // If you need more fine grained control on the transformation process you can register a custom transformer
        // for every class specifically. Do that either in the configure() method of a resource using "$configurator->registerTransformer()" or
        // in the api site config class using "$configurator->transformer->register()"
        
        // IMPORTANT: This method MUST return only a single resource data source. If an array of multiple,
        // or an iterable is returned an exception will be thrown.
        
        // Return types: This method accepts a multitude of different return types:
        // ExtBase objects, generic objects, TYPO3 db query results, BetterQuery instances, TYPO3 or doctrine query builders,
        // arrays or ResourceItem objects.
        
        // Any resource can transport "meta" information that will be available to the api consumer.
        // Please note: There is no automatic transformation on meta-data! All meta-data must be directly JSON encodable
        $context->setMeta(['feed' => 'main']);
        
        // In this case a News domain object is retrieved through the extbase repository and returned directly.
        // If NULL is returned by this method, it means there is no resource with the given id.
        
        // The conversion of extbase objects into arrays is done automatically by the builtin transformer.
        // Related domain objects are automatically created as related resources (https://jsonapi.org/format/#document-resource-object-related-resource-links)
        return $this->repository->findByUid((int)$id);
    }
    
    /**
     * @inheritDoc
     */
    public function findCollection(ResourceQuery $resourceQuery, ResourceCollectionContext $context)
    {
        // The second main player are "collections". A collection is basically a set of multiple resource objects.
        // Contrary to single resources, collections are retrieved using a resource query, instead of a unique id.
        // The resource query follows the "Fetching Data" definitions of the json api (https://jsonapi.org/format/#fetching)
        
        // IMPORTANT: This method MUST return only an iterable something as a data source. If only a single,
        // non iterable object is returned an exception will be thrown.
        
        // While not strictly necessary, it would suggest to keep the returned object instances of the same type
        // as far as possible.
        
        // Similar to getSingle() this method is quite resilient when it comes to return types:
        // ObjectStorage, iterable objects, TYPO3 db query results, BetterQuery instances, TYPO3 or doctrine query builders,
        // arrays or ResourceCollection objects.
        
        // Better Query - whats that? Before we go on, a short heads up. BetterQuery is an abstraction build on top
        // of the extbase and doctrine query builders, that allows you to build SELECT queries (in my mind) more intuitively.
        // It also provides you with the option to add "partial where statements" which is a thing the extbase query builder
        // lacks if you want to create a query programmatically. Partial statements allow you to "prepare" a query and
        // add your own where statement on top of the preconfigured constraints later. This problem is normally solved
        // using a so called "demand" object in extensions like "news" and the like.
        
        // To create a better query you have three options: a.) use the BetterRepository class as parent on your
        // extbase domain repository (instead the AbstractRepository), which gives you a "getQuery()" method,
        // that creates a new BetterQuery object for you. b.) use ANY extbase domain repository and create
        // a repository wrap $betterRepo = BetterRepository::getWrapper($yourRepositoryInstance); the wrapper
        // will now give you access to the "getQuery()" method. c.) don't use extbase and create a generic
        // BetterQuery object through the "DbService" provided by t3ba.
        // Either use dependency injection, or the static access like this: TypoContext::getInstance()->di()->cs()->db->getQuery('table_name')
        
        // Moving on, we now can use our fancy query object by applying constraints to it based on the resource query.
        // While you can theoretically everything yourself using a repository method t3fa provides gives you access
        // to the constraint builder, that transforms a resource query to a "partial where" on the provided better query.
        
        // Similar to a single resource, collections can also provide meta information
        // Please note: There is no automatic transformation on meta-data! All meta-data must be directly JSON encodable
        $context->setMeta(['feed' => 'main']);
        
        // Because BetterQuery is immutable we get the new, modified instance back
        // after the constraint builder was applied. Because we don't want additional constraints
        // on the query object, we simply return the configured query to be converted into an array representation
        return $context
            // The constraint builder is designed to prepare a BetterQuery object,
            // which is part of the t3ba extension by adding partial SQL query clauses based on the configured
            // type and property.
            ->getConstraintBuilder()
            // The sort constraint uses the json:api "sort" syntax to configure the given fields to be used for sorting
            ->addSortConstraint(['date', 'title'])
            // The filter constraint can be used to narrow down the list of possible results based on field criteria.
            // The first array (null in this example) is a list of fields that can be used as "exact-match" fields.
            // The second array is a list of fields that will be treated as "LIKE" in your sql query,
            // allowing you to match partial values in the fields provided.
            ->addFilterConstraint(null, ['title'])
            // The date range constraint is used to allow the lookup of news either on a specific day or in a range of dates
            ->addDateRangeConstraint('date')
            // The final step we apply our registered constraints to a query instance.
            ->apply($this->repository->getQuery());
        
    }
    
}
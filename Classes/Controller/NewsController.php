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
 * Last modified: 2021.06.25 at 14:42
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Controller;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\ContentElement\ConfigureContentElementInterface;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\ContentElement\ContentElementConfigurator;
use LaborDigital\T3ba\Tool\BackendPreview\BackendPreviewRendererContext;
use LaborDigital\T3ba\Tool\BackendPreview\BackendPreviewRendererInterface;
use LaborDigital\T3ba\Tool\Tca\ContentType\Builder\ContentType;
use LaborDigital\T3fa\Core\ContentElement\Controller\JsonContentActionController;
use LaborDigital\T3fa\Core\ContentElement\Response\JsonResponse;
use LaborDigital\T3faExample\Domain\Model\News;
use LaborDigital\T3faExample\Domain\Repository\NewsRepository;
use League\Route\Http\Exception\NotFoundException;

class NewsController extends JsonContentActionController
    implements ConfigureContentElementInterface, BackendPreviewRendererInterface
{
    /**
     * @var \LaborDigital\T3faExample\Domain\Repository\NewsRepository
     */
    protected $repository;
    
    public function __construct(NewsRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @inheritDoc
     */
    public static function configureContentElement(ContentElementConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->setTitle('faxBe.ce.news.list.title')
                     ->setDescription('faxBe.ce.news.list.desc')
                     ->setWizardTab('plugins')
                     ->setActions(['list']);
        
        $configurator->getVariant('detail')
                     ->setTitle('faxBe.ce.news.detail.title')
                     ->setDescription('faxBe.ce.news.detail.desc')
                     ->setWizardTab('plugins')
                     ->setActions(['detail']);
    }
    
    /**
     * @inheritDoc
     */
    public static function configureContentType(ContentType $type, ExtConfigContext $context): void
    {
        // As an example we want to our plugin to be sortable by a user selectable field.
        // Therefore we add two new fields to the TCA of the content element.
        $type->getTab(0)->addMultiple(static function () use ($type) {
            $type->getPalette('sorting')->addMultiple(static function () use ($type) {
                $type->getField('sort_field')
                     ->setLabel('faxBe.ce.news.list.field.sortField')
                     ->setDefault('date')
                     ->applyPreset()->select([
                        'date' => 'faxBe.ce.news.list.field.sortField.date',
                        'title' => 'faxBe.ce.news.list.field.sortField.title',
                    ]);
                
                $type->getField('direction')
                     ->setLabel('faxBe.ce.news.list.field.direction')
                     ->setDefault('asc')
                     ->applyPreset()->select([
                        'asc' => 'faxBe.ce.news.list.field.direction.asc',
                        'desc' => 'faxBe.ce.news.list.field.direction.desc',
                    ]);
            });
        });
    }
    
    public static function configureDetailContentType(): void
    {
        // We don't need any form fields for the detail view, so we can skip it
    }
    
    public function listAction(): JsonResponse
    {
        // The list action provides a list of news entries to the frontend
        // The frontend could now either query the news entries based on the configured sort order
        // from the /resources/news endpoint, or, we can include the initial state for the element
        // in our response.
        return $this->getJsonResponse()
            
            // We query the initial list of news already sorted based on the configured fields by the author.
                    ->withInitialStateQuery('news', [
                // The getData() method is part of T3BAs content type logic. It allows you access
                // to the raw data of the tt_content record in the database. You could also use
                // getDataModel() to retrieve more complex relations through an extbase object.
                // Note however, using getData() is always faster than using getDataModel()
                'sort' => [$this->getData()['sort_field'] => $this->getData()['direction']],
            ]);
    }
    
    public function detailAction(?News $news = null): JsonResponse
    {
        // If no news was resolved for our content element by extbase
        // we simply throw a not found exception, which will bubble up and convert the current page into a 404 response
        if (! $news) {
            throw new NotFoundException('No news was given');
        }
        
        return $this->getJsonResponse()
            // To tell our frontend component which news it should render,
            // we provide the domain object as a data. The transformation process
            // will convert it into a JSON ready array.
                    ->withData(['news' => $news])
            // Additionally we want the list of "categories" that are related to this
            // news object as well as the banner image included in the response.
            // Therefore we tell the data transformer that it should include all dependencies.
                    ->withDataTransformerOptions(['include' => true]);
    }
    
    /**
     * @inheritDoc
     */
    public function renderBackendPreview(BackendPreviewRendererContext $context)
    {
        // We can't really show anything for the editor when the "detail" plugin is included.
        // Therefore we simply skip the detail variant and only handle the list action.
        if ($context->getVariant() === 'detail') {
            return null;
        }
        
        // In order to show the editor which news will be displayed in the frontend
        // we query them in a similar fashion as our initial state query...
        $newsQuery = $this->repository
            ->getQuery()
            ->withOrder([
                $this->getData()['sort_field'] => $this->getData()['direction'],
            ])
            ->withLimit(5);
        
        // ... and use the record table renderer to display a nice HTML table
        // of the news records we resolved
        return $context->getUtils()->renderRecordTable(
            $this->repository, $newsQuery->getAll(true), ['title', 'date']
        );
    }
    
    
}
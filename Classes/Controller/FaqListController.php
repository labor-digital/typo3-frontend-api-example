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
 * Last modified: 2021.06.25 at 21:43
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
use LaborDigital\T3faExample\Configuration\Table\Faq\FaqListTable;
use LaborDigital\T3faExample\Domain\DataModel\FaqListDataModel;
use LaborDigital\T3faExample\Domain\Model\Faq\FaqList;

class FaqListController extends JsonContentActionController
    implements ConfigureContentElementInterface, BackendPreviewRendererInterface
{
    /**
     * @inheritDoc
     */
    public static function configureContentElement(ContentElementConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->setTitle('faxBe.ce.faqList.title')
                     ->setDescription('faxBe.ce.faqList.desc')
                     ->setWizardTab('landingPage')
                     ->setWizardTabLabel('faxBe.wizardTab.landingPage');
    }
    
    /**
     * @inheritDoc
     */
    public static function configureContentType(ContentType $type, ExtConfigContext $context): void
    {
        // The data model comes in handy if you want to resolve
        // relations between your data. In our case we create a data model
        // to map the lists to their ext base objects
        $type->setDataModelClass(FaqListDataModel::class);
        
        $type->getField('lists')
             ->setLabel('faxBe.ce.faqList.field.lists')
             ->applyPreset()
             ->relationGroup(FaqListTable::class, [
                 'maxItems' => 3,
                 'required',
                 'basePid' => '@pid.storage.faq',
             ])
             ->moveTo();
    }
    
    public function mainAction(): JsonResponse
    {
        return $this->getJsonResponse()
                    ->withData([
                        'lists' => $this->getDataModel()->getLists(),
                    ])
                    ->withDataTransformerOptions(['include' => true]);
    }
    
    /**
     * @inheritDoc
     */
    public function renderBackendPreview(BackendPreviewRendererContext $context)
    {
        return $context->getUtils()->renderRecordTable(
        // Its up to you how you specify the table name here
        // You can use Extbase Model classes, T3BA table configuration classes
        // or the actual name of the table
            FaqList::class,
            // Normally the record renderer receives an array of rows in order
            // to render them as a table. Pro - Tip as long it is an array,
            // you can also pass along extbase domain models
            $this->getDataModel()->getLists()->toArray(),
            ['title']
        );
    }
    
    
}
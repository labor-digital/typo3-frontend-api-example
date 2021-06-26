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
 * Last modified: 2021.06.26 at 15:54
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Controller;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\ContentElement\ConfigureContentElementInterface;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\ContentElement\ContentElementConfigurator;
use LaborDigital\T3ba\Tool\Tca\ContentType\Builder\ContentType;
use LaborDigital\T3fa\Core\ContentElement\Controller\JsonContentActionController;
use LaborDigital\T3fa\Core\ContentElement\Response\JsonResponse;

class DemoElementController extends JsonContentActionController implements ConfigureContentElementInterface
{
    /**
     * @inheritDoc
     */
    public static function configureContentElement(ContentElementConfigurator $configurator, ExtConfigContext $context): void
    {
        // JSON based content elements handle exactly the same as plugins,
        // however instead of plugins, a content element allows you to freely configure
        // the whole TCA array, instead of only a Flex-Form.
        
        // Otherwise you can refer to DemoPluginController for a detailed
        // explanation of the inner workings of this class.
        
        // The other configuration stays the same
        $configurator->setTitle('faxBe.ce.demo.title')
                     ->setDescription('faxBe.ce.demo.desc')
                     ->setActions(['main']);
        
        $configurator->getVariant('variant')
                     ->setTitle('faxBe.ce.demo.variant.title')
                     ->setDescription('faxBe.ce.demo.variant.desc')
                     ->setActions(['variant']);
    }
    
    /**
     * @inheritDoc
     */
    public static function configureContentType(ContentType $type, ExtConfigContext $context): void
    {
        // Similar to
        $type->getTab(0)->addMultiple(function () use ($type) {
            $type->getField('input')
                 ->setLabel('faxBe.ce.demo.field.input')
                 ->applyPreset()->input(['required']);
        });
    }
    
    public static function configureVariantContentType(ContentType $type, ExtConfigContext $context): void
    {
        // To configure fields
        $type->getTab(0)->addMultiple(function () use ($type) {
            $type->getField('text')
                 ->setLabel('faxBe.ce.demo.field.input')
                 ->applyPreset()->textArea(['rte', 'required']);
        });
    }
    
    public function mainAction(): JsonResponse
    {
        return $this->getJsonResponse()->withData(['input' => $this->getData()['input']]);
    }
    
    public function variantAction(): JsonResponse
    {
        return $this->getJsonResponse()->withData(['text' => $this->getData()['text']]);
    }
}
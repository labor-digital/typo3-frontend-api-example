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
 * Last modified: 2021.06.13 at 21:11
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
use LaborDigital\T3faExample\EventHandler\DataHook\CeControllerDataHooks;

class CeController extends JsonContentActionController implements
    ConfigureContentElementInterface,
    BackendPreviewRendererInterface
{
    /**
     * @inheritDoc
     */
    public static function configureContentElement(ContentElementConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->replaceOtherElement('header')
                     ->setActions(['header'])
                     ->registerSaveHook(CeControllerDataHooks::class, 'saveHeaderHook');
        
        $configurator->getVariant('textMedia')
                     ->replaceOtherElement('textmedia')
                     ->setActions(['textMedia']);
    }
    
    /**
     * @inheritDoc
     */
    public static function configureContentType(ContentType $type, ExtConfigContext $context): void
    {
        // TODO: Implement configureContentType() method.
    }
    
    public function headerAction(): JsonResponse
    {
        return $this->getJsonResponse()->withData([
                'header' => $this->getData()['header'],
            ]
        );
    }
    
    public function textMediaAction(): JsonResponse
    {
        dbge('text media!');
    }
    
    /**
     * @inheritDoc
     */
    public function renderBackendPreview(BackendPreviewRendererContext $context)
    {
        return 'BE HEADER';
    }
    
    public function renderTextMediaBackendPreview(BackendPreviewRendererContext $context)
    {
        return 'BE TEXT MEDIA';
    }
}
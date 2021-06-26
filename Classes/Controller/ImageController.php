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
 * Last modified: 2021.06.26 at 17:48
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

/**
 * Class ImageController
 *
 * This class is part of a real world project to show how you can alter built in elements
 * to match the exact needs of your client.
 *
 * @package LaborDigital\T3faExample\Controller
 * @see     \LaborDigital\T3faExample\Controller\TextController
 * @see     \LaborDigital\T3faExample\Controller\HeaderController
 */
class ImageController extends JsonContentActionController implements
    ConfigureContentElementInterface,
    BackendPreviewRendererInterface
{
    
    /**
     * @inheritDoc
     */
    public static function configureContentElement(ContentElementConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->replaceOtherElement('image');
    }
    
    /**
     * @inheritDoc
     */
    public static function configureContentType(ContentType $type, ExtConfigContext $context): void
    {
        $type->removeChildren([
            'header',
            'header_layout',
            'header_position',
            'header_date',
            'header_link',
            'subheader',
            'date',
            '_headers',
            'layout',
            '_appearanceLinks',
            'frame_class',
            '_gallerySettings',
            '_imagelinks',
            '_mediaAdjustments',
            'categories',
            'rowDescription',
        ]);
        $type->getField('image')->moveTo()
             ->applyPreset()
             ->relationImage([
                 'minItems' => 1,
                 'maxItems' => 5,
                 'cropVariants' => [
                     'default' => [
                         'title' => 'Default',
                         'aspectRatios' => [
                             '16:9' => '16:9',
                         ],
                     ],
                 ],
             ]);
    }
    
    public function mainAction(): JsonResponse
    {
        return $this->getJsonResponse()
                    ->withData([
                        'images' => $this->cs()->fal->getFile(
                            $this->getData()['uid'], 'tt_content', 'image', false
                        ),
                    ]);
    }
    
    /**
     * @inheritDoc
     */
    public function renderBackendPreview(BackendPreviewRendererContext $context)
    {
        return $context->getUtils()->renderFieldList(['image']);
    }
}
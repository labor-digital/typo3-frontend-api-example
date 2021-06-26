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
 * Last modified: 2021.06.26 at 15:41
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Controller;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\ContentElement\ConfigureContentElementInterface;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\ContentElement\ContentElementConfigurator;
use LaborDigital\T3ba\Tool\BackendPreview\BackendListLabelRendererInterface;
use LaborDigital\T3ba\Tool\Tca\ContentType\Builder\ContentType;
use LaborDigital\T3fa\Core\ContentElement\Controller\JsonContentActionController;
use LaborDigital\T3fa\Core\ContentElement\Response\JsonResponse;

/**
 * Class TextController
 *
 * This class is part of a real world project to show how you can alter built in elements
 * to match the exact needs of your client.
 *
 * @package LaborDigital\T3faExample\Controller
 * @see     \LaborDigital\T3faExample\Controller\HeaderController
 * @see     \LaborDigital\T3faExample\Controller\ImageController
 */
class TextController extends JsonContentActionController
    implements ConfigureContentElementInterface, BackendListLabelRendererInterface
{
    /**
     * @inheritDoc
     */
    public static function configureContentElement(ContentElementConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->replaceOtherElement('text');
    }
    
    /**
     * @inheritDoc
     */
    public static function configureContentType(ContentType $type, ExtConfigContext $context): void
    {
        $type->getTab(1)->remove();
        $type->removeChildren([
            'header',
            'header_layout',
            'header_position',
            'header_date',
            'header_link',
            'header_layout',
            'subheader',
            'date',
            '_headers',
            'text-frames',
            'categories',
            'rowDescription',
        ]);
    }
    
    public function mainAction(): JsonResponse
    {
        return $this->getJsonResponse()->withData([
            'bodyText' => $this->getData()['bodytext'],
        ]);
    }
    
    /**
     * @inheritDoc
     */
    public function renderBackendListLabel(array $row, array $options): string
    {
        return strip_tags($row['bodytext']);
    }
    
    
}
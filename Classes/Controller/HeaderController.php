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
 * Last modified: 2021.06.26 at 17:46
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
use Neunerlei\Inflection\Inflector;

/**
 * Class HeaderController
 *
 * This class is part of a real world project to show how you can alter built in elements
 * to match the exact needs of your client.
 *
 * @package LaborDigital\T3faExample\Controller
 * @see     \LaborDigital\T3faExample\Controller\TextController
 * @see     \LaborDigital\T3faExample\Controller\ImageController
 */
class HeaderController extends JsonContentActionController
    implements ConfigureContentElementInterface, BackendPreviewRendererInterface
{
    /**
     * @inheritDoc
     */
    public static function configureContentElement(ContentElementConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->replaceOtherElement('header')
                     ->setBackendListLabelRenderer(['header']);
    }
    
    /**
     * @inheritDoc
     */
    public static function configureContentType(ContentType $type, ExtConfigContext $context): void
    {
        $type->removeChildren([
            'bodytext',
            'header_position',
            'header_date',
            'header_link',
            'subheader',
            'date',
            'layout',
            '_appearanceLinks',
            'frame_class',
            'categories',
            'rowDescription',
        ]);
        
        $type->getField('header')->addConfig(['eval' => 'trim,required']);
        
        // Modify H tags
        $headerLayout = $type->getField('header_layout');
        $headerLayout->moveTo('after:_general');
        $raw = $headerLayout->getRaw();
        unset($raw['config']['items'], $raw['config']['itemsProcFunc']);
        $headerLayout->setRaw($raw);
        $headerLayout->applyPreset()->select([
            'h2' => 'faxBe.ce.header.size.h2',
            'h3' => 'faxBe.ce.header.size.h3',
            'h4' => 'faxBe.ce.header.size.h4',
            'h5' => 'faxBe.ce.header.size.h5',
            'h6' => 'faxBe.ce.header.size.h6',
        ], ['default' => 'h2']);
    }
    
    public function mainAction(): JsonResponse
    {
        return $this->getJsonResponse()
                    ->withData([
                        'text' => $this->getData()['header'],
                        'tag' => $this->getTag($this->getData()),
                        'id' => Inflector::toSlug($this->getData()['header']),
                    ]);
    }
    
    /**
     * @inheritDoc
     */
    public function renderBackendPreview(BackendPreviewRendererContext $context)
    {
        $tag = $this->getTag($this->getData());
        $context->setHeader($context->getHeader() . "&nbsp;<em>$tag</em>");
        
        return '<' . $tag . ' style="margin: 0;">' . $this->getData()['header'] . '</' . $tag . '>';
    }
    
    
    /**
     * Internal helper to generate the correct html tag for this headline
     *
     * @param   array  $data
     *
     * @return string
     */
    protected function getTag(array $data): string
    {
        $layout = $data['header_layout'] ?? null;
        
        return ! is_string($layout) || is_numeric($layout) ? 'h2' : $layout;
    }
}
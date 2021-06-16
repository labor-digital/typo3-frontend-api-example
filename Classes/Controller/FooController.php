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
 * Last modified: 2021.06.08 at 14:06
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Controller;


use LaborDigital\T3ba\Core\Exception\NotImplementedException;
use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\ContentElement\ConfigureContentElementInterface;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\ContentElement\ContentElementConfigurator;
use LaborDigital\T3ba\Tool\Tca\ContentType\Builder\ContentType;
use LaborDigital\T3fa\Core\ContentElement\Controller\JsonContentActionController;
use LaborDigital\T3fa\Core\ContentElement\HtmlSerializer;

class FooController extends JsonContentActionController implements ConfigureContentElementInterface
{
    
    /**
     * @inheritDoc
     */
    public static function configureContentElement(ContentElementConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->setTitle('demo2')
                     ->setDescription('faxBe.ce.demo.desc')
                     ->setActions(['main']);
    }
    
    /**
     * @inheritDoc
     */
    public static function configureContentType(ContentType $type, ExtConfigContext $context): void
    {
        $type->getTab(0)->addMultiple(function () use ($type) {
            $type->getField('input')
                 ->setLabel('faxBe.ce.demo.field.input')
                 ->applyPreset()->input(['required']);
        });
    }
    
    public function mainAction()
    {
        $response = $this->getJsonResponse();
        $response = $response->withData([
            'myProperty' => true,
        ]);
        $data = ['foo' => true];
        
        return HtmlSerializer::serialize($data);
//        return 'test';
        throw new NotImplementedException('foo!');
        dbge(get_class($this->view));
    }
}
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
 * Last modified: 2021.06.25 at 20:34
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Configuration\Table\Faq;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Table\ConfigureTcaTableInterface;
use LaborDigital\T3ba\Tool\Tca\Builder\Type\Table\TcaTable;

class FaqItemTable implements ConfigureTcaTableInterface
{
    /**
     * @inheritDoc
     */
    public static function configureTable(TcaTable $table, ExtConfigContext $context): void
    {
        $table->setTitle('faxBe.t.faq.item.title')
              ->setHidden()
              ->setLabelColumn('question')
              ->setSearchColumns(['question', 'answer']);
        
        $type = $table->getType();
        $type->getTab(0)->addMultiple(static function () use ($type) {
            $type->getField('question')
                 ->setLabel('faxBe.t.faq.item.field.question')
                 ->applyPreset()->input(['maxLength' => 256]);
            
            $type->getField('answer')
                 ->setLabel('faxBe.t.faq.item.field.answer')
                 ->applyPreset()->textArea(['rte']);
        });
    }
    
}
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
 * Last modified: 2021.05.31 at 13:58
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Configuration\Table;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Table\ConfigureTcaTableInterface;
use LaborDigital\T3ba\Tool\Tca\Builder\Type\Table\TcaTable;

class NewsTable implements ConfigureTcaTableInterface
{
    
    /**
     * @inheritDoc
     */
    public static function configureTable(TcaTable $table, ExtConfigContext $context): void
    {
        $table->setTitle('faxBe.t.news.title')
              ->setLabelColumn('title');
        
        $type = $table->getType();
        $type->getTab(0)->addMultiple(static function () use ($type) {
            $type->getField('title')
                 ->setLabel('faxBe.t.news.field.title')
                 ->applyPreset()->input();
            
            $type->getField('slug')
                 ->setLabel('faxBe.t.news.field.slug')
                 ->applyPreset()->slug(['title']);
            
            $type->getField('teaser')
                 ->setLabel('faxBe.t.news.field.teaser')
                 ->applyPreset()->textArea();
            
            $type->getField('body')
                 ->setLabel('faxBe.t.news.field.body')
                 ->applyPreset()->textArea(['rte']);
            
            $type->getField('banner_image')
                 ->setLabel('faxBe.t.news.field.bannerImage')
                 ->applyPreset()->relationFile(['maxItems' => 1]);
            
            $type->getField('date')
                 ->setLabel('faxBe.t.news.field.date')
                 ->applyPreset()->date();
        });
        
        $type->getNewTab()->setLabel('faxBe.t.news.tab.categories')
             ->addMultiple(static function () use ($type) {
                 $type->getField('categories')
                      ->setLabel('faxBe.t.news.field.categories')
                      ->applyPreset()->categorize(['maxItems' => 2]);
             });
    }
    
}
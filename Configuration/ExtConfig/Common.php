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
 * Last modified: 2021.06.25 at 21:50
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Configuration\ExtConfig;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Core\ConfigureTypoCoreInterface;
use LaborDigital\T3ba\ExtConfigHandler\Core\TypoCoreConfigurator;
use LaborDigital\T3ba\ExtConfigHandler\Translation\ConfigureTranslationInterface;
use LaborDigital\T3ba\ExtConfigHandler\Translation\TranslationConfigurator;
use LaborDigital\T3ba\ExtConfigHandler\TypoScript\ConfigureTypoScriptInterface;
use LaborDigital\T3ba\ExtConfigHandler\TypoScript\TypoScriptConfigurator;

class Common implements ConfigureTranslationInterface, ConfigureTypoCoreInterface, ConfigureTypoScriptInterface
{
    /**
     * @inheritDoc
     */
    public static function configureCore(TypoCoreConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->registerFileLog(['key' => 'deprecation', 'namespace' => 'TYPO3\\CMS\\deprecations']);
    }
    
    /**
     * @inheritDoc
     */
    public static function configureTranslation(TranslationConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->registerNamespace('faxBe', 'locallang_be.xlf');
    }
    
    /**
     * @inheritDoc
     */
    public static function configureTypoScript(TypoScriptConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator->registerStaticTsDirectory()
                     ->registerPageTsConfigImport();
    }
    
    
}
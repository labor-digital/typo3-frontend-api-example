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
 * Last modified: 2021.06.24 at 19:20
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Configuration\ExtConfig\Site\Common;


use LaborDigital\T3ba\ExtConfig\SiteBased\SiteConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Routing\Site\ConfigureSiteRoutingInterface;
use LaborDigital\T3ba\ExtConfigHandler\Routing\Site\SiteRoutingConfigurator;
use LaborDigital\T3faExample\Configuration\Table\NewsTable;
use LaborDigital\T3faExample\Controller\NewsController;

class Routing implements ConfigureSiteRoutingInterface
{
    /**
     * @inheritDoc
     */
    public static function configureSiteRouting(SiteRoutingConfigurator $configurator, SiteConfigContext $context): void
    {
        // As you can see, T3FA plays nicely alongside of the T3BA configuration options.
        // This way you have all configuration options that match a single site in a single configuration class
        $configurator->registerExtbasePlugin(
            'newsDetail',
            '/{news}',
            NewsController::class, 'detail',
            ['@pid.page.news.detail'],
            [
                'dbArgs' => [
                    'news' => [NewsTable::class, 'slug'],
                ],
            ]
        );
        
    }
}
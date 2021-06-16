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
 * Last modified: 2021.06.02 at 20:35
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Configuration\ExtConfig;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\Link\ConfigureLinksInterface;
use LaborDigital\T3ba\ExtConfigHandler\Link\DefinitionCollector;
use LaborDigital\T3ba\ExtConfigHandler\Pid\ConfigurePidsInterface;
use LaborDigital\T3ba\ExtConfigHandler\Pid\PidCollector;
use LaborDigital\T3ba\Tool\Link\LinkService;
use LaborDigital\T3fa\ExtConfigHandler\Api\Page\Link\PageLinkCollector;
use LaborDigital\T3fa\ExtConfigHandler\Api\Page\Link\PageLinkProviderInterface;

class PidsAndLinks implements ConfigurePidsInterface, ConfigureLinksInterface, PageLinkProviderInterface
{
    /**
     * @inheritDoc
     */
    public static function configurePids(PidCollector $collector, ExtConfigContext $context): void
    {
        $collector->setMultiple([
            'storage' => [
                'news' => 2,
            ],
            'page' => [
                'news' => [
                    'list' => 5,
                    'detail' => 3,
                ],
            ],
        ]);
    }
    
    /**
     * @inheritDoc
     */
    public static function configureLinks(DefinitionCollector $collector, ExtConfigContext $context)
    {
        // To avoid creating link definitions over and over again, you can use a "link set" using the better api extension.
        // For our example we create a "newsList" link which we want to provide in our page link list, below
        $collector->getDefinition('newsList')->setPid('@pid.page.news.list');
    }
    
    /**
     * @inheritDoc
     */
    public static function provideLinks(PageLinkCollector $collector, LinkService $linkService, int $pid): void
    {
        // Frontend api tries to pick up the concepts of the better api extension and pass them along into
        // the api world. The link provider is used to add static/global links that will be available on every
        // "page" resource object in the "links" section.
        
        // In this example we use the "newsList" link set we created in the configureLinks() method and
        // register it as static link. You can provide every link set using only the link set key.
        $collector->registerLink('newsList');
        
        // Alternatively you can create a new link object and pass it along as well
        $collector->registerLink('home', $linkService->getLink()->withPid(1));
        
        // The links are generated for every page independently, so you can page specific links on the fly.
        $collector->registerLink('currentPage', $linkService->getLink()->withPid($pid));
    }
    
    
}
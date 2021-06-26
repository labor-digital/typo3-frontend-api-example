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
 * Last modified: 2021.06.26 at 16:12
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
use LaborDigital\T3faExample\Controller\NewsController;

class PidsAndLinks implements ConfigurePidsInterface, ConfigureLinksInterface, PageLinkProviderInterface
{
    /**
     * @inheritDoc
     */
    public static function configurePids(PidCollector $collector, ExtConfigContext $context): void
    {
        $collector->setMultiple([
            'menu' => [
                'footer' => 11,
            ],
            'storage' => [
                'news' => 2,
                'faq' => 17,
            ],
            'page' => [
                'home' => [
                    'website' => 1,
                    'landingPage' => 9,
                ],
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
        
        // We also create a set to link on a news detail
        $collector->getDefinition('newsDetail')
            // The "?" means that this link set requires an argument called "news" to be set.
                  ->addToArgs('news', '?')
                  ->setControllerClass(NewsController::class)
                  ->setControllerAction('detail')
                  ->setPid('@pid.page.news.detail');
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
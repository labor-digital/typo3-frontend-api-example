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
 * Last modified: 2021.06.23 at 12:18
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Configuration\ExtConfig\Site\Common;


use LaborDigital\T3ba\ExtConfig\SiteBased\SiteConfigContext;
use LaborDigital\T3fa\ExtConfigHandler\Api\ApiConfigurator;
use LaborDigital\T3fa\ExtConfigHandler\Api\BundleCollector;
use LaborDigital\T3fa\ExtConfigHandler\Api\ConfigureApiInterface;
use LaborDigital\T3fa\ExtConfigHandler\Api\Resource\ResourceCollector;
use LaborDigital\T3faExample\Api\LayoutObject\MainMenu;
use LaborDigital\T3faExample\Api\LayoutObject\StaticElements;
use LaborDigital\T3faExample\Api\Resource\NewsResource;
use LaborDigital\T3faExample\Api\Resource\ProxyResource;
use LaborDigital\T3faExample\Api\Route\TestController;
use LaborDigital\T3faExample\Configuration\ExtConfig\PidsAndLinks;
use LaborDigital\T3faExample\Domain\DataModel\Page\PageDataModel;
use LaborDigital\T3faExample\Middleware\DemoMiddleware;
use LaborDigital\T3faExample\Middleware\GroupMiddleware;

/**
 * Class Api
 *
 * This configuration is used to tell the frontend api which resources and routes should be available on a single
 * TYPO3 site. {@link https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/SiteHandling/Basics.html}
 *
 * If you are familiar with T3ba, you will already know the concept of "site-based" config options.
 *
 * As a quick reminder:
 * Site-based means, that different TYPO3 sites can have
 * different configuration classes. By default this class applies to ALL existing sites in your installation.
 * If you want to limit the configuration to specific sites you can use two ways:
 * 1. Use the "SiteKeyProviderInterface" to define your constraints.
 * 2. Collect your site config classes under the ExtConfig\Site\$YourSiteIdentifier special namespace.
 * Everything in the "ExtConfig\Site\Common" namespace will be done for every site in your installation.
 * In our example we have a second site called "Landingpage" which will ONLY apply to the "langingpage" site
 * that is configured for TYPO3.
 *
 * Site based options can be spotted easily, as they provide you with the "SiteConfigContext" instead of the
 * normal "ExtConfigContext".
 *
 * Keep in mind, that, if your configuration applies to multiple sites it will be executed multiple times,
 * once for each site it applies to. Therefore you should keep it stateless.
 * You can always check which site gets currently configured using $context->getSite() or $context->getSiteKey()
 *
 * @package LaborDigital\T3faExample\Configuration\ExtConfig\Site\Common\Api
 * @see     \LaborDigital\T3ba\ExtConfig\Interfaces\SiteKeyProviderInterface
 */
class Api implements ConfigureApiInterface
{
    /**
     * @inheritDoc
     */
    public static function registerBundles(BundleCollector $collector): void
    {
        // Bundles can be provided by extensions to configure the frontend API site to match their needs.
        
        // The bundles will be loaded before the registerResources / configureSite methods are executed.
        // This allows you to modify every change provided by a bundle yourself.
        
        // If your site does not need any bundles, simply ignore this method.
        
        // Pro-Tip: All default functions of the frontend api extension are served as bundles
        // that get automatically injected into the collector. If you really want to can disable those bundles
        // by yourself.
    }
    
    /**
     * @inheritDoc
     */
    public static function registerResources(ResourceCollector $collector, SiteConfigContext $context): void
    {
        // Resource classes are used to translate TYPO3 data into an api ready resource.
        // The source class is basically an abstract repository/mapper to convert an api request into a db query, further api request
        // or static data map. Each resource also has a mapped route to it which follows the json:api standard.
        
        $collector->register(NewsResource::class)
                  ->register(ProxyResource::class);
    }
    
    /**
     * @inheritDoc
     */
    public static function configureSite(ApiConfigurator $configurator, SiteConfigContext $context): void
    {
        // Configures a single site for the frontend api. The options range from routing to resource handling.
        
        // You can easily register custom routes using the routing() option.
        // The api router is build on top of PHP league's "route" bundle, therefore most of the
        // config options will be quite similar: https://route.thephpleague.com/5.x/routes/
        
        // By default the api links will generate their links relative to the current request.
        // However, should ever the need arise to force the api url you can do so by providing the static api host.
        // Multiple sites can provide different api hosts.
        // $configurator->setApiHost('https://foo.bar');
        
        // Using the "routes" method, you retrieve a route collector object. It allows you
        // to specify the routes you need for your application. The collection also acts as a "group",
        // meaning you can group any number of routes under a common prefix.
        // In our example we register a /test route, which will be available under /api/test later.
        // Let's say we want to group multiple routes for chat messages under the same prefix like /chat/messages, /chat/post, and so on.
        // You could create a specific "chat" route group like: $chatRoutes = $configurator->routing()->routes('/chat');
        // Now all routes you register using $chatRoutes automatically get prefixed using the group prefix.
        $routes = $configurator->routing()->routes();
        
        // You have three options to register middlewares:
        // 1. globally using $configurator->routing()->registerGlobalMiddleware(),
        // 2. per route group, as you see here,
        // 3. specifically for each route (as you see below)
        $routes->registerMiddleware(GroupMiddleware::class);
        
        // You can register routes either trough the $routes->registerRoute() method,
        // or using the HTTP method shortcuts like get, post, put, ect.
        // The first parameter is the "path" that should be handled by the controller and action.
        // You can also register wildcard routes like this: https://route.thephpleague.com/5.x/routes/#wildcard-routes
        $routes->get('/test', [TestController::class, 'indexAction'])
               ->registerMiddleware(DemoMiddleware::class, [
                   // Similar to TYPO3 middlewares, you can sort middlewares either by their class name or unique identifier.
                   // As you can see here, you can even execute a route specific middleware in before a specific group middleware.
                   'before' => GroupMiddleware::class,
               ]);
        
        $routes->post('/test', [TestController::class, 'postAction']);
        
        // The "page" resource is a powerful endpoint to retrieve all information of the pages in your
        // TYPO3 installation. To enhance it, you find multiple configuration options under the "page()" method.
        $page = $configurator->page();
        
        // For example, you can provide a list of static/global links your frontend can access.
        // Therefore you register a link provider which tells the frontend api which links to register
        $page->registerLinkProvider(PidsAndLinks::class);
        
        // By default only a sub-set of fields in the "pages" table will be passed to the frontend.
        // To extend the field list you can use a custom "DataModel". The data model is basically an extbase
        // model that gets mapped with a row in the "pages" table. Like any other resource object the "getters" are used
        // to determine the properties available in the page info. In our example we want to make the "media" field public
        // so the frontend can access it.
        $page->setDataModelClass(PageDataModel::class);
        
        // Additionally we want the "media" field to "slide". This means if a page does not have entries in the "media" itself,
        // TYPO3 should try to fetch the data of the parent page until it either reaches the "root" page or a value.
        $page->setDataSlideFields(['media']);
        
        // To create a main menu on our page we use the "layout object" feature. Layout objects are more or less "static" objects
        // of data that you can retrieve through the /api/resources/layoutObject endpoint. They come preequipped with a host
        // of generators for menus, language switchers, but can also render content layout content elements by id
        // or typoScript object path.
        $layout = $configurator->layoutObjects();
        
        // In our case we only want a simple object that provides us with a main menu as a first step.
        // The key provided first, defines the id with which you can retrieve the element later.
        // Note that this is a common config, meaning all configuration we do here, will be available on all sites.
        $layout->registerObject('mainMenu', MainMenu::class);
        
        // Next, we register some static content elements we want to place in our layout.
        // In a real world application this would be your fe-login form or an extension widget.
        $layout->registerObject('ce', StaticElements::class);
        
        // Moving on, we want to provide some TYPO3 translation labels for our frontend implementation.
        // Therefore T3FA provides the /api/resources/translation endpoint. It provides a compiled, json encoded
        // list of translation labels, most of the common frontend translators should understand.
        // To configure it, we simply tell the translator option which files should be included.
        $configurator->translation()->registerLabelFile('locallang.xlf');
    }
}
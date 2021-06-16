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
 * Last modified: 2021.06.11 at 20:39
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Controller;


use LaborDigital\T3ba\ExtBase\Controller\BetterContentActionController;
use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\Plugin\ConfigurePluginInterface;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\Plugin\PluginConfigurator;
use LaborDigital\T3ba\Tool\BackendPreview\BackendPreviewRendererContext;
use LaborDigital\T3ba\Tool\BackendPreview\BackendPreviewRendererInterface;
use LaborDigital\T3fa\Core\ContentElement\Controller\JsonContentElementControllerTrait;
use LaborDigital\T3faExample\Domain\Repository\NewsRepository;

class DemoPluginController extends BetterContentActionController implements
    ConfigurePluginInterface, BackendPreviewRendererInterface
{
    // This plugin contains two different plugins, one renders html as a result,
    // the other will render a json object for the SPA frontend to handle.
    // While normally the JsonContentActionController should be the go-to controller for json based
    // content elements and plugins, in this case we use the ContentActionController from T3ba.
    // To use the json api features we can then use the JsonContentElementControllerTrait instead.
    use JsonContentElementControllerTrait;
    
    /**
     * @inheritDoc
     */
    public static function configurePlugin(PluginConfigurator $configurator, ExtConfigContext $context): void
    {
        // Similar to content elements, plugins can also be used as json based elements.
        /**
         * @see \LaborDigital\T3faExample\Controller\DemoElementController
         * for an example on how to configure content elements instead
         */
        $configurator
            ->setActions(['main']);
        
        $configurator->getVariant('json')
            
            // Important note: Using setNoCacheActions() does not do anything for you here.
            // because the caching relevant options are handled by the JsonResponse object
            // down in the json action. So, if you are working with the json api, only use the
            // setActions() method.
            
                     ->setActions(['json']);
    }
    
    public function mainAction(): void
    {
        // This is a normal HTML action you know in a fluid plugin all to well. Nothing special to keep in mind here.
        $this->view->assign('header', $this->getData()['header']);
    }
    
    public function jsonAction()
    {
        // The ContentActionController automatically wraps the execution of the element action
        // into a so called "errorBoundary" which tries to handle errors in a content element locally
        // without crashing the whole website. Because we don't use that controller here
        // we can simulate the boundary using the jsonErrorBoundary() method in the JsonContentElementControllerTrait
        return $this->jsonErrorBoundary(function () {
            // JsonContentElementControllerTrait also provides you with the getJsonResponse()
            // method that creates a new response object to configure the json which gets passed
            // to the frontend framework.
            return $this->getJsonResponse()
                
                // It allows you to provide css classes that will be passed in the content element definition
                // Those classes also have built in support for the core css classes defined as "Frame", "Spacer Before" or "Spacer After"
                        ->withCssClasses(['demoElement'])
                
                // The frontend api has an advanced caching layer that automatically keeps track of
                // how long which element should be cached and also detects if changes occur.
                // But using the json response you can fine-tune the cache settings even further,
                // by defining exactly how long this content element is cacheable...
//                        ->withCacheLifetime(60 * 15)
                
                // ... or additional cache tags ...
                        ->withCacheTags(['my_element'])
                
                // ... or disable the cache all together
//                    ->withCacheEnabled(false)
                
                // You have two ways of transporting information to the frontend framework component
                // that will represent this plugin. Everything you see here, works for content elements as well, tho!
                
                // The first option to pass information is called "data". These are basically your "view" variables.
                // Pro-tip: You can use $this->view->assign('key', $value); as well to provide data.
                // The json view will automatically inherit all variables from your view.
                
                // Data is automatically transformed using the matching transformers,
                // which means you can pass along virtually any value and they should be converted to
                // their json representation on the fly.
                
                        ->withData([
                    'hello' => 'world',
                    'news' => $this->objectManager->get(NewsRepository::class)->findByUid(1),
                    
                    // Extbase Plugins and content elements are still able to receive get parameters
                    // so if you add ?tx_t3faexample_demopluginjson[foo]=something to your API request
                    // you can access it through the extbase request object
                    'requestArg' => $this->request->getArgument('foo'),
                ])
                
                // The second way to provide information is the so called "initial state query".
                // The idea behind this feature is, that plugins like a news list, or a news detail
                // always rely on a resource to be rendered. Normally you would therefore use your
                // frontend framework component to asynchronously load the data through the resource api.
                // To avoid that "initial state request" to the api you can include that initial data
                // in the definition for the content element.
                
                // This query uses the same syntax as the resource apis GET parameters or the
                // or the resource repositories query syntax to resolve and include the data.
                // It will be inlined into the content element definition and can be accessed by
                // your frontend framework directly.
                        ->withInitialStateQuery('news', [
                    'filter' => [
                        'title' => 'test',
                    ],
                ], [
                    // Using Transformer options you can also control how the related resources should be handled.
                    'transformerOptions' => [
                        'include' => true,
                    ],
                ]);
        });
    }
    
    /**
     * @inheritDoc
     */
    public function renderBackendPreview(BackendPreviewRendererContext $context)
    {
        return 'Demo Plugin';
    }
    
}
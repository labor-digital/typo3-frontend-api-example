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
 * Last modified: 2021.06.26 at 16:05
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Controller;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\Plugin\ConfigurePluginInterface;
use LaborDigital\T3ba\ExtConfigHandler\ExtBase\Plugin\PluginConfigurator;
use LaborDigital\T3ba\Tool\BackendPreview\BackendPreviewRendererContext;
use LaborDigital\T3ba\Tool\BackendPreview\BackendPreviewRendererInterface;
use LaborDigital\T3ba\Tool\OddsAndEnds\SerializerUtil;
use LaborDigital\T3fa\Core\ContentElement\Controller\JsonContentActionController;
use LaborDigital\T3fa\Core\ContentElement\HtmlSerializer;
use LaborDigital\T3fa\Core\ContentElement\Response\JsonResponse;
use LaborDigital\T3fa\Core\Link\ApiLink;

class RestElementController extends JsonContentActionController implements
    ConfigurePluginInterface,
    BackendPreviewRendererInterface
{
    /**
     * @inheritDoc
     */
    public static function configurePlugin(PluginConfigurator $configurator, ExtConfigContext $context): void
    {
        $configurator
            ->setTitle('faxBe.ce.restDemo.title')
            ->setDescription('faxBe.ce.restDemo.desc')
            ->setActions(['main', 'post']);
    }
    
    public function mainAction(): JsonResponse
    {
        // NOTE: This is the explicit way to create a link, scroll down to getFormInfo() for a faster method of
        // retrieving the same information.
        
        // To make it easier on ourselves in the frontend we prepare the link to
        // our post action in the backend and the frontend can then simply send a POST request containing
        // the form data to it.
        // For that we use the ApiLink link builder provided by the T3FA package.
        $link = (new ApiLink())
            // You can either provide a static path to your endpoint,
            // or use the reverse routing capabilities to link to a specific route using it's name
            ->withRouteName('resource-contentElement-single')
            // We use the single resource route, which requires a unique id for the link generation.
            // As we want to link to this exact element again, we use the uid provided in the element data.
            ->withRouteArguments(['id' => $this->getData()['uid']])
            // To tell extbase that we want to use the "post" action, we have to add the tx_...[controller],
            // [action] and [method] get parameters. We could either do that manually using the withQueryParams method
            // or, in our case we use the T3BA link builder to build the link for this element
            // and simply modify the action name.
            ->withSlugLinkBuilder(
            // The getLink() method on a BetterActionController (which is a parent of the JsonContentActionController)
            // returns a link object that is already aware of the elements request, meaning all
            // links will be namespaced with said get parameters.
                $this->getLink()->withControllerAction('post')
            );
        
        // While possible it is still quite a hassle to write that every time you want to provide
        // that kind of information. The getFormInfo helper does the same thing, and provides
        // you with an array containing both the endpoint url and the form field name prefix for this element
        $formInfo = $this->getFormInfo('post');
        
        return $this->getJsonResponse()
                    ->withData([
                        'post' => $formInfo,
                        'postLink' => $link,
                    ]);
    }
    
    public function postAction(): JsonResponse
    {
        return $this->getJsonResponse()->withData([
            // This example simply returns the list of arguments that have been passed to the action
            'arguments' => $this->request->getArguments(),
        ]);
    }
    
    /**
     * @inheritDoc
     */
    public function renderBackendPreview(BackendPreviewRendererContext $context)
    {
        // IMPORTANT: Tech demo: This is probably nothing you would want to do in an every day workflow.
        
        // While not really practical, this demonstrates that links are build correctly
        // even if the request is simulated for a backend preview.
        $response = $this->simulateRequest('main')->getContent();
        
        // The json content is serialized in a HTML wrap, so it does not break when you use fluid styled contents.
        // Or any other TYPO magic that depends on a HTML markup. This has the drawback, that you have to
        // deserialize the payload before you can use it
        [$data] = HtmlSerializer::unserialize($response);
        
        return '<pre><code>' . htmlentities(SerializerUtil::serializeJson($data, ['pretty'])) . '</code></pre>';
    }
    
    
}
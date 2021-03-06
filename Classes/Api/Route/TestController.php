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
 * Last modified: 2021.06.25 at 18:02
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Api\Route;


use LaborDigital\T3fa\Core\Routing\Controller\AbstractRouteController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TestController extends AbstractRouteController
{
    public function indexAction(): ResponseInterface
    {
        // Whenever you want to respond with a "OK" message, you can use the getJsonOkResponse
        // factory method. It returns a application/json response containing only a "status" => "OK" body.
        return $this->getJsonOkResponse();
    }
    
    public function postAction(ServerRequestInterface $request): ResponseInterface
    {
        // Post actions get their post content using the getParsedBody() method of the request.
        // T3FA supports most of the commonly used body serialization options out of the box.
        return $this->getJsonResponse(
            $request->getParsedBody() ?? ['data' => ['non given']]
        );
    }
}
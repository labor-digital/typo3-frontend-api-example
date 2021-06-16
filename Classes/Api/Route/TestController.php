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
 * Last modified: 2021.06.10 at 21:04
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Api\Route;


use LaborDigital\T3fa\Core\Resource\Repository\ResourceRepository;
use LaborDigital\T3fa\Core\Routing\Controller\AbstractRouteController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TestController extends AbstractRouteController
{
    public function indexAction(): ResponseInterface
    {
        return $this->getJsonOkResponse();
    }
    
    public function postAction(ServerRequestInterface $request): ResponseInterface
    {
        $this->getService(ResourceRepository::class)->getResource('contentElement', 10);
        
        return $this->getJsonResponse(
            $request->getParsedBody() ?? ['data' => ['non given']]
        );
    }
}
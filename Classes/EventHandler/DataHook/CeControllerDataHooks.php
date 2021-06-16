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
 * Last modified: 2021.06.11 at 20:24
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\EventHandler\DataHook;


use LaborDigital\T3ba\Core\Di\PublicServiceInterface;
use LaborDigital\T3ba\Tool\DataHook\DataHookContext;
use LaborDigital\T3ba\Tool\Rendering\FlashMessageRenderingService;

class CeControllerDataHooks implements PublicServiceInterface
{
    /**
     * @var \LaborDigital\T3ba\Tool\Rendering\FlashMessageRenderingService
     */
    protected $flashMessages;
    
    /**
     * DemoDataHooks constructor.
     *
     * @param   \LaborDigital\T3ba\Tool\Rendering\FlashMessageRenderingService  $flashMessages
     */
    public function __construct(FlashMessageRenderingService $flashMessages)
    {
        $this->flashMessages = $flashMessages;
    }
    
    public function saveHeaderHook(DataHookContext $c): void
    {
        $this->flashMessages->addOk(
            'This header has been saved in table: ' . $c->getTableName(),
            'Header updated');
    }
}
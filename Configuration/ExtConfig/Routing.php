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
 * Last modified: 2021.06.24 at 18:39
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Configuration\ExtConfig;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3fa\ExtConfigHandler\Routing\ApiRoutingConfigurator;
use LaborDigital\T3fa\ExtConfigHandler\Routing\ConfigureApiRoutingInterface;

class Routing implements ConfigureApiRoutingInterface
{
    /**
     * @inheritDoc
     */
    public static function configureApiRouting(ApiRoutingConfigurator $configurator, ExtConfigContext $context): void
    {
        // For up and health checks you can enable the /api/up route. It is not cached and
        // will check if TYPO3 is up and running correctly.
        $configurator->enableUpRoute();
        
        // The api has an built-in endpoint for the scheduler extension (which has to be installed,
        // in order for this option to have an effect). The endpoint is accessible on /api/scheduler/run to run
        // the whole scheduler task list If you provide the id of a given task like /api/scheduler/run/1 for task
        // ID 1 you can also execute a single task. While you are running in a dev environment and execute a single
        // task it will always be forced to run, ignoring the cronjob configuration;
        
        // To prevent unwanted executions of scheduler tasks either a single, or multiple secure tokens
        // must be provided. The tokens are basically "passwords" and must be provided when the route is called.
        // The token can either be provided using the "x-t3fa-token" header or via query parameter "token",
        // if that option was enabled by setting "allowTokenInQuery" to true
        
        // Note: If Typo3 is in dev mode (Typo-Context: Development), you don't need to provide the token.
        $configurator->enableSchedulerRoute('some-secure-token');
    }
}
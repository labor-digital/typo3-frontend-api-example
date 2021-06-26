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
 * Last modified: 2021.06.25 at 18:51
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Api\LayoutObject;


use LaborDigital\T3fa\Api\Resource\Factory\LayoutObject\LayoutObjectContext;
use LaborDigital\T3fa\Core\LayoutObject\AbstractLayoutObject;

class MainMenu extends AbstractLayoutObject
{
    /**
     * @inheritDoc
     */
    public function generate(LayoutObjectContext $context): array
    {
        // To generate the main menu of the current site you don't have to provide any configuration
        // The first two levels will be automatically generated as menu. You can use this in your frontend
        // directly to build the main menu of the application.
        return $this->makePageMenu([
            // You have multiple options, tho to extend the menus to your needs.
            // In addition to powerful post processors on a "global" and "per-item" level,
            // you can leverage TYPO3's build-in data processors to look up additional fields or file references
            'fileFields' => ['media'],
            'additionalFields' => ['description'],
        ]);
    }
    
}
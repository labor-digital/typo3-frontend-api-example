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
 * Last modified: 2021.06.24 at 19:37
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Api\Resource\PostProcessor;


use LaborDigital\T3ba\Tool\Link\LinkService;
use LaborDigital\T3fa\Core\Resource\Transformer\ResourcePostProcessorInterface;

class NewsPostProcessor implements ResourcePostProcessorInterface
{
    /**
     * @var \LaborDigital\T3ba\Tool\Link\LinkService
     */
    protected $linkService;
    
    public function __construct(LinkService $linkService)
    {
        $this->linkService = $linkService;
    }
    
    /**
     * @inheritDoc
     */
    public function process(array $result, $value): array
    {
        // This is a simple, demo post processor, it is used to automatically add
        // the link to the news to each record that gets processed.
        // $value is in our case an instance of the News domain model which our "newsDetail" link set
        // which we defined in the Configuration\ExtConfig\PidsAndLinks class, expects.
        $result['link'] = $this->linkService->getLink('newsDetail', ['news' => $value])->build();
        
        // The post processor must either return the initial value, or the modified one
        return $result;
    }
    
}